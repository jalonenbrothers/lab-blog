<?php

declare(strict_types=1);

require_once __DIR__ . '/src/ContentStore.php';
require_once __DIR__ . '/src/BlogMarkdown.php';
require_once __DIR__ . '/src/layout.php';

const CONTENT_ROOT = __DIR__ . '/content';

$store = new ContentStore(CONTENT_ROOT);

// ---------- routing ----------
$route = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$route = rtrim($route, '/') ?: '/';

// static content passthrough (style.css etc.) — the web server normally
// handles these; guard so PHP-built-in server users get them too.
if (preg_match('#^/(style\.css|favicon\.ico)$#', $route, $m)) {
    $file = __DIR__ . '/' . $m[1];
    if (is_file($file)) {
        header('Content-Type: ' . ($m[1] === 'style.css' ? 'text/css' : 'image/x-icon'));
        readfile($file);
        exit;
    }
}

// images: /content/images/<name>
if (preg_match('#^/content/(images/.+)$#', $route, $m)) {
    $path = $store->imagePath($m[1]);
    if ($path === null || !is_file($path)) {
        http_response_code(404);
        echo page('Not found', '<h1>404</h1><p>Image not found.</p>');
        exit;
    }

    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $mime = match ($ext) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png'         => 'image/png',
        'gif'         => 'image/gif',
        'webp'        => 'image/webp',
        'svg'         => 'image/svg+xml',
        default       => 'application/octet-stream',
    };

    header('Content-Type: ' . $mime);
    readfile($path);
    exit;
}

// ---------- home ----------
if ($route === '/') {
    $posts = $store->posts();
    $cats  = $store->categories();

    $nav = '';
    foreach ($cats as $slug => $cat) {
        $count = count($store->posts($slug));
        $nav .= '<a href="/category/' . e($slug) . '">' . e($cat['title']) . '</a> <small>(' . $count . ')</small>' . "\n";
    }

    $cards = '';
    foreach ($posts as $post) {
        $card = '<article class="post-card">';
        if (!empty($post['image'])) {
            $card .= '<a href="/post/' . e($post['slug']) . '"><img class="card-img" src="/content/' . e($post['image']) . '" alt="" loading="lazy"></a>';
        }
        $card .= '<div class="card-body">';
        $card .= '<h2><a href="/post/' . e($post['slug']) . '">' . e($post['title']) . '</a></h2>';
        $card .= '<div class="meta">' . e($post['date'] ?? '');
        if (!empty($post['category']) && $store->category($post['category'])) {
            $card .= ' · <a href="/category/' . e($post['category']) . '">' . e($store->category($post['category'])['title']) . '</a>';
        }
        $card .= '</div>';
        if (!empty($post['summary'])) {
            $card .= '<p>' . e($post['summary']) . '</p>';
        }
        $card .= '</div></article>' . "\n";
        $cards .= $card;
    }

    $body = $nav !== '' ? "<nav class=\"cats\">{$nav}</nav>\n<div class=\"post-list\">\n{$cards}</div>" : "<div class=\"post-list\">\n{$cards}</div>";

    echo page('Home', $body);
    exit;
}

// ---------- single post ----------
if (preg_match('#^/post/([a-z0-9-]+)$#', $route, $m)) {
    $post = $store->post($m[1]);

    if ($post === null) {
        http_response_code(404);
        echo page('Not found', '<h1>404</h1><p>No such post.</p>');
        exit;
    }

    $body = '<article class="post">' . "\n";
    $body .= '<h1>' . e($post['title'] ?? $m[1]) . '</h1>' . "\n";
    $body .= '<div class="meta">' . e($post['date'] ?? '');
    if (!empty($post['category']) && $store->category($post['category'])) {
        $body .= ' · <a href="/category/' . e($post['category']) . '">' . e($store->category($post['category'])['title']) . '</a>';
    }
    $body .= "</div>\n";
    $body .= BlogMarkdown::render($post['body']) . "\n";
    $body .= "</article>\n<p><a href=\"/\">← All posts</a></p>";

    echo page($post['title'] ?? $m[1], $body);
    exit;
}

// ---------- category ----------
if (preg_match('#^/category/([a-z0-9-]+)$#', $route, $m)) {
    $cat = $store->category($m[1]);

    if ($cat === null) {
        http_response_code(404);
        echo page('Not found', '<h1>404</h1><p>No such category.</p>');
        exit;
    }

    $posts = $store->posts($m[1]);

    $body = '<h1>' . e($cat['title']) . '</h1>' . "\n";
    if (!empty($cat['description'])) {
        $body .= '<p class="muted">' . e($cat['description']) . '</p>' . "\n";
    }

    $cards = '';
    foreach ($posts as $post) {
        $cards .= '<article class="post-card"><div class="card-body">'
            . '<h2><a href="/post/' . e($post['slug']) . '">' . e($post['title']) . '</a></h2>'
            . '<div class="meta">' . e($post['date'] ?? '') . '</div>'
            . (!empty($post['summary']) ? '<p>' . e($post['summary']) . '</p>' : '')
            . '</div></article>' . "\n";
    }

    $body .= $cards !== '' ? "<div class=\"post-list\">\n{$cards}</div>" : '<p class="muted">No posts yet.</p>';
    $body .= "\n<p><a href=\"/\">← All posts</a></p>";

    echo page($cat['title'], $body);
    exit;
}

// ---------- about ----------
if ($route === '/about') {
    $aboutFile = CONTENT_ROOT . '/about.md';
    $md = is_file($aboutFile) ? (string) file_get_contents($aboutFile) : 'This blog is written by agents, published via git push.';
    echo page('About', '<article class="post">' . BlogMarkdown::render($md) . '</article>');
    exit;
}

http_response_code(404);
echo page('Not found', '<h1>404</h1><p>The page you wanted does not exist.</p>');