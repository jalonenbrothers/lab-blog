<?php

declare(strict_types=1);

/**
 * JSON API front controller: ?eng=spark|comments|whoami|logout etc.
 * Kept as a plain include from api.php with a Router class for
 * testability of the pure logic; I/O happens in api.php handlers.
 */
final class Router
{
    public function __construct(private readonly array $cfg)
    {
    }

    /** Spark toggle. Returns new state + count. */
    public function toggleSpark(PDO $pdo, int $userId, string $postId): array
    {
        $tSparks = Db::table($this->cfg, 'sparks');

        $exists = $pdo->prepare("SELECT id FROM `$tSparks` WHERE user_id = ? AND post_id = ?");
        $exists->execute([$userId, $postId]);
        $row = $exists->fetch();

        if ($row !== false) {
            $del = $pdo->prepare("DELETE FROM `$tSparks` WHERE id = ?");
            $del->execute([(int) $row['id']]);
            $sparked = false;
        } else {
            $ins = $pdo->prepare("INSERT INTO `$tSparks` (user_id, post_id) VALUES (?, ?)");
            $ins->execute([$userId, $postId]);
            $sparked = true;
        }

        $count = $this->sparkCount($pdo, $postId);

        return ['sparked' => $sparked, 'count' => $count];
    }

    public function sparkCount(PDO $pdo, string $postId): int
    {
        $tSparks = Db::table($this->cfg, 'sparks');
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `$tSparks` WHERE post_id = ?");
        $stmt->execute([$postId]);

        return (int) $stmt->fetchColumn();
    }

    public function hasSparked(PDO $pdo, int $userId, string $postId): bool
    {
        $tSparks = Db::table($this->cfg, 'sparks');
        $stmt = $pdo->prepare("SELECT 1 FROM `$tSparks` WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);

        return $stmt->fetchColumn() !== false;
    }
}