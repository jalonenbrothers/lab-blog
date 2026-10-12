<?php

declare(strict_types=1);

/**
 * Moderation queue UI. Access: /engagement/moderation.php?key=<moderator_key>
 * Plain, no-auth-by-design (long random key = bearer capability).
 */

require_once __DIR__ . '/lib/Db.php';
require_once __DIR__ . '/lib/Sanitiser.php';
require_once __DIR__ . '/lib/Queue.php';

$configDir = __DIR__;
$cfg = Db::cfg($configDir);

$given = $_GET['key'] ?? '';
if (!hash_equals($cfg['moderator_key'], (string) $given)) {
    http_response_code(403);
    exit('forbidden');
}

$pdo    = Db::pdo($configDir);
$tComm  = Db::table($cfg, 'comments');
$tUsers = Db::table($cfg, 'users');
$tLog   = Db::table($cfg, 'moderation_log');

// actions
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $do = $_POST['do'] ?? '';

    if ($id > 0) {
        if ($do === 'approve') {
            Queue::approve($pdo, $tComm, $tLog, 'comment', $id);
        } elseif ($do === 'delete') {
            Queue::delete($pdo, $tComm, $tLog, 'comment', $id);
        }
    }
    header('Location: ?key=' . urlencode($cfg['moderator_key']));
    exit;
}

$pending = Queue::pending($pdo, $tComm, $tUsers);
$count = count($pending);

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>Engagement moderation</title>
<style>
body { font: 15px/1.6 -apple-system, sans-serif; margin: 0; background: #f6f6f3; color: #232320; }
main { max-width: 720px; margin: 2rem auto; padding: 0 1rem; }
.item { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1rem; }
.meta { color: #777; font-size: 0.82rem; }
.body { margin: 0.5rem 0; white-space: pre-wrap; }
form { display: inline; }
button { cursor: pointer; padding: 0.35rem 0.9rem; border-radius: 6px; border: 1px solid #bbb; background: #fff; }
button.approve { border-color: #3a7d44; color: #3a7d44; }
button.delete { border-color: #b04040; color: #b04040; }
h1 { font-size: 1.2rem; }
.empty { color: #777; }
</style>
</head>
<body>
<main>
<h1>Moderation — <?= $count ?> pending</h1>
<?php if ($count === 0): ?>
<p class="empty">Queue clear. Nothing pending.</p>
<?php endif; ?>
<?php foreach ($pending as $c): ?>
<div class="item">
  <div class="meta"><?= e($c['author']) ?> on <strong><?= e($c['post_id']) ?></strong> · <?= e($c['created_at']) ?></div>
  <div class="body"><?= e($c['body']) ?></div>
  <form method="post"><input type="hidden" name="key" value="<?= e($cfg['moderator_key']) ?>"><input type="hidden" name="id" value="<?= (int) $c['id'] ?>"><button class="approve" name="do" value="approve">Approve</button></form>
  <form method="post"><input type="hidden" name="key" value="<?= e($cfg['moderator_key']) ?>"><input type="hidden" name="id" value="<?= (int) $c['id'] ?>"><button class="delete" name="do" value="delete">Delete</button></form>
</div>
<?php endforeach; ?>
</main>
</body>
</html>