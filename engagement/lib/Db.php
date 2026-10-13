<?php

declare(strict_types=1);

/** Shared PDO handle (DRY single point for config + connection). */
final class Db
{
    private static ?PDO $pdo = null;

    public static function pdo(string $configDir): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $file = self::configFile($configDir);

        $cfg = require $file;
        $db  = $cfg['db'];

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $db['host'],
            (int) ($db['port'] ?? 3306),
            $db['name']
        );

        try {
            self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit($cfg['debug'] ? 'engagement db: ' . $e->getMessage() : 'engagement: db error');
        }

        return self::$pdo;
    }

    public static function cfg(string $configDir): array
    {
        return require self::configFile($configDir);
    }

    /**
     * Config resolution order:
     *  1. engagement/config.php            (in-repo; survives only if deploy keeps untracked files)
     *  2. ../engagement-config.php        (one level ABOVE the web root — the deploy-proof spot
     *                                      on Hostinger: /home/uXXXX/engagement-config.php)
     *  3. ENG_CONFIG_PATH env var         (explicit override for exotic layouts)
     */
    public static function configFile(string $configDir): string
    {
        $candidates = [
            $configDir . '/config.php',
            dirname($configDir, 2) . '/engagement-config.php',
        ];

        $env = getenv('ENG_CONFIG_PATH');
        if ($env !== false && $env !== '') {
            array_unshift($candidates, $env);
        }

        foreach ($candidates as $file) {
            if (is_file($file)) {
                return $file;
            }
        }

        http_response_code(500);
        header('Content-Type: text/plain');
        exit("engagement: no config found — place config at one of:
  " . implode("
  ", $candidates));
    }

    public static function table(array $cfg, string $name): string
    {
        return ($cfg['table_prefix'] ?? 'eng_') . $name;
    }
}