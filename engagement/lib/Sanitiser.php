<?php

declare(strict_types=1);

/**
 * Sanitiser — the ONE pass every user string goes through.
 * Strip-tags-first (no HTML allowlist in v1), escape on render,
 * mask links/emails, enforce length caps.
 */
final class Sanitiser
{
    public const MAX_BODY  = 2000;
    public const MAX_NAME  = 50;

    /** Masked on SUBMIT; unmasking is never offered (operator rule). */
    public static function body(string $raw): string
    {
        $s = self::normalise($raw);
        $s = strip_tags($s);
        $s = trim($s);

        if (mb_strlen($s) > self::MAX_BODY) {
            $s = mb_substr($s, 0, self::MAX_BODY);
        }

        $s = self::maskEmails($s);
        $s = self::maskUrls($s);

        return $s;
    }

    public static function name(string $raw): string
    {
        $s = self::normalise($raw);
        $s = strip_tags($s);
        $s = trim($s);
        // collapse control chars
        $s = preg_replace('/[\x00-\x1F\x7F]/u', '', $s) ?? $s;

        if (mb_strlen($s) > self::MAX_NAME) {
            $s = mb_substr($s, 0, self::MAX_NAME);
        }

        return $s;
    }

    /** Escape for HTML output. ALWAYS use at render time. */
    public static function e(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }

    /** URL-token masking: http(s), www., bare domains, www-less links. */
    private static function maskUrls(string $s): string
    {
        $s = preg_replace('/https?:\/\/\S+/iu', '[link]', $s) ?? $s;
        $s = preg_replace('/\bwww\.[a-z0-9.-]+\S*/iu', '[link]', $s) ?? $s;
        $s = preg_replace('/\b[a-z0-9.-]+\.(com|net|org|io|dev|app|co|eu|uk|me|info|xyz|site)(\/\S*)?\b/iu', '[link]', $s) ?? $s;

        return $s;
    }

    private static function maskEmails(string $s): string
    {
        return preg_replace('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/iu', '[email]', $s) ?? $s;
    }

    private static function normalise(string $s): string
    {
        $s = str_replace("\r\n", "\n", $s);
        $s = str_replace("\r", "\n", $s);

        return $s;
    }
}