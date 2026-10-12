<?php

declare(strict_types=1);

/** Session auth for engagement (google_sub-based, plain PHP session). */
final class Auth
{
    public static function userId(): ?int
    {
        return isset($_SESSION['eng_user_id']) ? (int) $_SESSION['eng_user_id'] : null;
    }

    public static function requireUser(): int
    {
        $id = self::userId();
        if ($id === null) {
            throw new RuntimeException('auth_required', 401);
        }

        return $id;
    }

    /** Login-or-create from verified Google claims; returns user id. */
    public static function loginWithGoogle(PDO $pdo, string $table, array $claims, string $salt): int
    {
        $sub   = $claims['sub'];
        $name  = Sanitiser::name($claims['name'] !== '' ? $claims['name'] : 'Reader');
        $email = $claims['email'];
        $hash  = hash('sha256', $salt . '|' . $email);

        $stmt = $pdo->prepare("SELECT id FROM `$table` WHERE google_sub = ? FOR UPDATE");
        $stmt->execute([$sub]);
        $row = $stmt->fetch();

        if ($row !== false) {
            $id = (int) $row['id'];
        } else {
            $ins = $pdo->prepare("INSERT INTO `$table` (google_sub, name, email_hash) VALUES (?, ?, ?)");
            $ins->execute([$sub, $name, $hash]);
            $id = (int) $pdo->lastInsertId();
        }

        session_regenerate_id(true);
        $_SESSION['eng_user_id'] = $id;

        return $id;
    }

    public static function logout(): void
    {
        unset($_SESSION['eng_user_id']);
    }
}