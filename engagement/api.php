<?php

declare(strict_types=1);

/**
 * Engagement Kit JSON front controller.
 * Mount: /engagement/api  (rewritten to api.php)
 * Endpoints (all JSON, all POST except GETs noted):
 *   GET  ?action=state&post=<id>      -> sparked?, count, comments?, user?
 *   POST ?action=google-login         -> {credential: <GIS id token>}
 *   POST ?action=spark&post=<id>      -> toggle (auth required)
 *   POST ?action=comment&post=<id>    -> {body} -> pending (auth + rate limit)
 *   GET  ?action=pending-count       -> moderation tally
 *   POST ?action=logout
 */

require_once __DIR__ . '/lib/Db.php';
require_once __DIR__ . '/lib/Sanitiser.php';
require_once __DIR__ . '/lib/GoogleJwt.php';
require_once __DIR__ . '/lib/Auth.php';
require_once __DIR__ . '/lib/RateLimit.php';
require_once __DIR__ . '/lib/Router.php';
require_once __DIR__ . '/lib/Queue.php';

$configDir = __DIR__;
$cfg = Db::cfg($configDir);
$pdo = Db::pdo($configDir);

session_start();

header('Content-Type: application/json; charset=utf-8');

/** JSON exit helper. */
function jout(array $data, int $code = 200): never
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_GET['action'] ?? '';
$postId = isset($_GET['post']) ? preg_replace('/[^a-z0-9-]/', '', strtolower((string) $_GET['post'])) : '';

$router  = new Router($cfg);
$tUsers  = Db::table($cfg, 'users');
$tComm   = Db::table($cfg, 'comments');
$tLimits = Db::table($cfg, 'rate_limits');
$tLog    = Db::table($cfg, 'moderation_log');

try {
    switch ($action) {
        case 'state':
            if ($postId === '') {
                jout(['error' => 'post required'], 400);
            }
            $userId = Auth::userId();
            jout([
                'user'      => $userId !== null ? ['name' => selfUserName($pdo, $tUsers, $userId)] : null,
                'sparked'   => $userId !== null && $router->hasSparked($pdo, $userId, $postId),
                'count'     => $router->sparkCount($pdo, $postId),
                'comments'  => Queue::approved($pdo, $tComm, $tUsers, $postId),
                'pending'   => false, // comments submitted this session show "pending moderation" hint client-side
            ]);

        case 'google-login':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                jout(['error' => 'post required'], 405);
            }
            $raw = file_get_contents('php://input');
            $data = json_decode((string) $raw, true);
            $credential = (string) ($data['credential'] ?? '');

            if ($credential === '') {
                jout(['error' => 'credential required'], 400);
            }

            if (!RateLimit::allow($pdo, $tLimits, 'glogin:' . ($_SERVER['REMOTE_ADDR'] ?? 'cli'), 10, 3600)) {
                jout(['error' => 'too many attempts'], 429);
            }

            $jwt = new GoogleJwt($cfg['google_client_id'], sys_get_temp_dir());
            $claims = $jwt->verify($credential);

            if ($claims === null) {
                jout(['error' => 'invalid credential'], 401);
            }

            $salt = hash('sha256', $cfg['moderator_key']); // stable per-site salt
            $id = Auth::loginWithGoogle($pdo, $tUsers, $claims, $salt);
            Queue::log($pdo, $tLog, 'login', 'user', $id, 'google');

            jout(['ok' => true, 'user' => ['name' => selfUserName($pdo, $tUsers, $id)]]);

        case 'spark':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                jout(['error' => 'post required'], 405);
            }
            if ($postId === '') {
                jout(['error' => 'post required'], 400);
            }
            $userId = Auth::requireUser();
            jout($router->toggleSpark($pdo, $userId, $postId));

        case 'comment':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                jout(['error' => 'post required'], 405);
            }
            if ($postId === '') {
                jout(['error' => 'post required'], 400);
            }
            $userId = Auth::requireUser();

            if (!RateLimit::allow($pdo, $tLimits, "comment:u$userId", 5, 3600)) {
                jout(['error' => 'rate limited — try again later'], 429);
            }

            $raw = file_get_contents('php://input');
            $data = json_decode((string) $raw, true);
            $rawBody = (string) ($data['body'] ?? '');

            $body = Sanitiser::body($rawBody);

            if (mb_strlen(trim($body)) < 2) {
                jout(['error' => 'comment too short'], 422);
            }

            $masked = $rawBody !== $body;
            $id = Queue::submit($pdo, $tComm, $userId, $postId, $body, $masked);

            jout(['ok' => true, 'status' => 'pending', 'id' => $id, 'masked' => $masked]);

        case 'pending-count':
            jout(['pending' => Queue::pendingCount($pdo, $tComm)]);

        case 'logout':
            Auth::logout();
            jout(['ok' => true]);

        default:
            jout(['error' => 'unknown action'], 404);
    }
} catch (RuntimeException $e) {
    if ($e->getCode() === 401) {
        jout(['error' => 'login required'], 401);
    }
    throw $e;
}

/** Display name for the session user. */
function selfUserName(PDO $pdo, string $tUsers, int $id): string
{
    $stmt = $pdo->prepare("SELECT name FROM `$tUsers` WHERE id = ?");
    $stmt->execute([$id]);

    return (string) $stmt->fetchColumn();
}