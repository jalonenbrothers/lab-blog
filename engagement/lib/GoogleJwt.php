<?php

declare(strict_types=1);

/**
 * GoogleJwt — verifies a Google ID token (RS256) against Google's JWKS.
 * Zero dependencies: openssl_verify + streams for fetch, JWKS cached
 * to a file with TTL. No SDK, no curl requirement.
 */
final class GoogleJwt
{
    private const JWKS_URI  = 'https://www.googleapis.com/oauth2/v3/certs';
    private const ISSUER    = 'https://accounts.google.com';
    private const ISSUER2   = 'accounts.google.com';

    public function __construct(
        private readonly string $clientId,
        private readonly string $cacheDir,
        private readonly int $cacheTtl = 86400,
    ) {
    }

    /**
     * @return array{sub:string,name:string,email:string}|null
     *         verified claims, or null on ANY failure (fail-closed).
     */
    public function verify(string $jwt): ?array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }

        [$header, $payload, $sig] = $parts;

        $h = self::jsonDecode(self::b64url($header));
        $p = self::jsonDecode(self::b64url($payload));

        if ($h === null || $p === null) {
            return null;
        }

        // fail-closed checks
        if (($h['alg'] ?? '') !== 'RS256') {
            return null;
        }

        $iss = $p['iss'] ?? '';
        if ($iss !== self::ISSUER && $iss !== self::ISSUER2) {
            return null;
        }

        if (($p['aud'] ?? '') !== $this->clientId) {
            return null;
        }

        if (!isset($p['exp']) || (int) $p['exp'] < time()) {
            return null;
        }

        if (!isset($p['sub']) || $p['sub'] === '') {
            return null;
        }

        $kid = $h['kid'] ?? '';
        $jwks = $this->jwks();
        $key = $jwks[$kid] ?? null;

        if ($key === null) {
            return null;
        }

        $expected = $header . '.' . $payload;  // RS256 signs the full "h.p" ASCII
        $sigRaw = self::b64url($sig);
        $ok = openssl_verify(
            $expected,
            $sigRaw,
            $key,
            OPENSSL_ALGO_SHA256
        );

        if ($ok !== 1) {
            return null;
        }

        return [
            'sub'   => (string) $p['sub'],
            'name'  => (string) ($p['name'] ?? ''),
            'email' => (string) ($p['email'] ?? ''),
        ];
    }

    /** @return array<string,\OpenSSLAsymmetricKey> kid => key */
    private function jwks(): array
    {
        $cacheFile = $this->cacheDir . '/google_jwks.json';

        $cached = null;
        if (is_file($cacheFile)) {
            $age = time() - (int) filemtime($cacheFile);
            if ($age < $this->cacheTtl) {
                $cached = json_decode((string) file_get_contents($cacheFile), true);
            }
        }

        $raw = $cached;
        if ($raw === null) {
            $raw = $this->fetchJwks();
            if ($raw === null) {
                return [];
            }
            @mkdir($this->cacheDir, 0775, true);
            file_put_contents($cacheFile, json_encode($raw));
        }

        $keys = [];
        foreach ($raw['keys'] ?? [] as $jwk) {
            if (($jwk['kty'] ?? '') !== 'RSA' || !isset($jwk['n'], $jwk['e'])) {
                continue;
            }

            $n = $jwk['n'];  // base64url strings
            $e = $jwk['e'];

            $der = self::rsaDer($n, $e);
            if ($der === null) {
                continue;
            }

            $key = openssl_pkey_get_public($der);
            if ($key !== false) {
                $keys[(string) $jwk['kid']] = $key;
            }
        }

        return $keys;
    }

    /** @return array<string,mixed>|null */
    private function fetchJwks(): ?array
    {
        $ctx = stream_context_create(['http' => ['timeout' => 5]]);
        $body = @file_get_contents(self::JWKS_URI, false, $ctx);

        if ($body === false) {
            return null;
        }

        $data = json_decode($body, true);

        return is_array($data) ? $data : null;
    }

    /** Minimal JWK (n,e base64url) -> PEM public key (SPKI). */
    private static function rsaDer(string $nB64, string $eB64): ?string
    {
        $n = self::b64url($nB64);
        $e = self::b64url($eB64);

        if ($n === '' || $e === '') {
            return null;
        }

        // SubjectPublicKeyInfo: SEQ { SEQ { OID rsaEncryption, NULL },
        //                           BIT STRING { SEQ { INTEGER n, INTEGER e } } }
        $algId = "\x30\x0d\x06\x09\x2a\x86\x48\x86\xf7\x0d\x01\x01\x01\x05\x00";
        $rsaInts = self::derSeq(self::derInt($n) . self::derInt($e));
        $bitString = self::derLen(0x03, "\x00" . $rsaInts) . "\x00" . $rsaInts;
        $spki = self::derSeq($algId . $bitString);

        return "-----BEGIN PUBLIC KEY-----\n"
            . chunk_split(base64_encode($spki), 64, "\n")
            . "-----END PUBLIC KEY-----\n";
    }

    private static function derInt(string $bin): string
    {
        if (ord($bin[0]) > 0x7f) {
            $bin = "\x00" . $bin;
        }

        return self::derLen(0x02, $bin) . $bin;
    }

    private static function derSeq(string $inner): string
    {
        return self::derLen(0x30, $inner) . $inner;
    }

    /** DER TLV header with correct short/long length forms. */
    private static function derLen(int $tag, string $payload): string
    {
        $len = strlen($payload);

        if ($len < 0x80) {
            return chr($tag) . chr($len);
        }

        $lenBytes = ltrim(pack('N', $len), "\x00");

        return chr($tag) . chr(0x80 | strlen($lenBytes)) . $lenBytes;
    }

    private static function b64url(string $s): string
    {
        return base64_decode(strtr($s, '-_', '+/') . str_repeat('=', (4 - strlen($s) % 4) % 4)) ?: '';
    }

    /** @return array<string,mixed>|null */
    private static function jsonDecode(string $s): ?array
    {
        $d = json_decode($s, true);

        return is_array($d) ? $d : null;
    }
}