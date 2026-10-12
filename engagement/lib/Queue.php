<?php

declare(strict_types=1);

/** Moderation queue + log. */
final class Queue
{
    public static function submit(PDO $pdo, string $tComments, int $userId, string $postId, string $body, bool $masked): int
    {
        $stmt = $pdo->prepare("INSERT INTO `$tComments` (post_id, user_id, body, status, masked) VALUES (?, ?, ?, 'pending', ?)");
        $stmt->execute([$postId, $userId, $body, (int) $masked]);

        return (int) $pdo->lastInsertId();
    }

    /** @return list<array<string,mixed>> pending, newest first */
    public static function pending(PDO $pdo, string $tComments, string $tUsers, int $limit = 100): array
    {
        $stmt = $pdo->prepare("SELECT c.id, c.post_id, c.body, c.created_at, u.name AS author
            FROM `$tComments` c JOIN `$tUsers` u ON u.id = c.user_id
            WHERE c.status = 'pending' ORDER BY c.id DESC LIMIT " . (int) $limit);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function approve(PDO $pdo, string $tComments, string $tLog, string $targetType, int $id): bool
    {
        $stmt = $pdo->prepare("UPDATE `$tComments` SET status='approved', moderated_at=NOW() WHERE id = ? AND status='pending'");
        $stmt->execute([$id]);
        $ok = $stmt->rowCount() === 1;

        if ($ok) {
            self::log($pdo, $tLog, 'approve', $targetType, $id);
        }

        return $ok;
    }

    public static function delete(PDO $pdo, string $tComments, string $tLog, string $targetType, int $id): bool
    {
        $stmt = $pdo->prepare("UPDATE `$tComments` SET status='deleted', moderated_at=NOW() WHERE id = ? AND status='pending'");
        $stmt->execute([$id]);
        $ok = $stmt->rowCount() === 1;

        if ($ok) {
            self::log($pdo, $tLog, 'delete', $targetType, $id);
        }

        return $ok;
    }

    /** @return list<array<string,mixed>> approved comments for a post, newest first */
    public static function approved(PDO $pdo, string $tComments, string $tUsers, string $postId): array
    {
        $stmt = $pdo->prepare("SELECT c.id, c.body, c.created_at, u.name AS author
            FROM `$tComments` c JOIN `$tUsers` u ON u.id = c.user_id
            WHERE c.post_id = ? AND c.status = 'approved'
            ORDER BY c.id DESC LIMIT 200");
        $stmt->execute([$postId]);

        return $stmt->fetchAll();
    }

    public static function pendingCount(PDO $pdo, string $tComments): int
    {
        return (int) $pdo->query("SELECT COUNT(*) FROM `$tComments` WHERE status='pending'")->fetchColumn();
    }

    public static function log(PDO $pdo, string $tLog, string $action, string $targetType, int $targetId, ?string $detail = null): void
    {
        $stmt = $pdo->prepare("INSERT INTO `$tLog` (action, target_type, target_id, detail) VALUES (?, ?, ?, ?)");
        $stmt->execute([$action, $targetType, $targetId, $detail]);
    }
}