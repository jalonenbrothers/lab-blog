<?php

declare(strict_types=1);

/** DB-enforced rate limiting (fixed-window buckets). */
final class RateLimit
{
    /**
     * @param int $max   events per window
     * @param int $windowSeconds window size
     */
    public static function allow(PDO $pdo, string $table, string $bucket, int $max, int $windowSeconds): bool
    {
        $windowStart = gmdate('Y-m-d H:i:s', (int) (time() / $windowSeconds) * $windowSeconds);

        $stmt = $pdo->prepare("INSERT INTO `$table` (bucket, window_start, count)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE count = count + 1");
        $stmt->execute([$bucket, $windowStart]);

        $check = $pdo->prepare("SELECT count FROM `$table` WHERE bucket = ? AND window_start = ?");
        $check->execute([$bucket, $windowStart]);

        return (int) $check->fetchColumn() <= $max;
    }
}