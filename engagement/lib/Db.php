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

        $file = $configDir . '/config.php';
        if (!is_file($file)) {
            http_response_code(500);
            exit('engagement: config.php missing — copy config.example.php');
        }

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
        $file = $configDir . '/config.php';
        if (!is_file($file)) {
            http_response_code(500);
            exit('engagement: config.php missing — copy config.example.php');
        }

        return require $file;
    }

    public static function table(array $cfg, string $name): string
    {
        return ($cfg['table_prefix'] ?? 'eng_') . $name;
    }
}