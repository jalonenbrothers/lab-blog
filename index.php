<?php

declare(strict_types=1);

require_once __DIR__ . '/src/ContentStore.php';
require_once __DIR__ . '/src/BlogMarkdown.php';
require_once __DIR__ . '/src/layout.php';

const CONTENT_ROOT = __DIR__ . '/content';

$store = new ContentStore(CONTENT_ROOT);

/** Render a post card (home/category listings). */
function post_card(array $post, ContentStore $store): string
{
    $pinned = !empty($post['pinned']);
    $card = '<article class="post-card' . ($pinned ? ' pinned' : '') . '">';
    if ($pinned) {
        $card .= '<span class="pin-badge" title="Featured post">★ Featured</span>';
    }
    if (!empty($post['image'])) {
        $card .= '<a class="card-img-link" href="/post/' . e($post['slug']) . '"><img class="card-img" src="/content/' . e($post['image']) . '" alt="" loading="lazy"></a>';
    }
    $card .= '<div class="card-body">';
    $card .= '<h2><a href="/post/' . e($post['slug']) . '">' . e($post['title']) . '</a></h2>';
    $card .= '<div class="meta">' . e($post['date'] ?? '');
    if (!empty($post['category']) && $store->category($post['category'])) {
        $card .= ' · <a href="/category/' . e($post['category']) . '">' . e($store->category($post['category'])['title']) . '</a>';
    }
    if (!empty($post['author']) && $store->author($post['author'])) {
        $a = $store->author($post['author']);
        $card .= ' · <span class="author">' . e($a['name'] ?? $post['author']) . '</span>';
    }
    $card .= '</div>';
    if (!empty($post['summary'])) {
        $card .= '<p>' . e($post['summary']) . '</p>';
    }
    $tags = $store->postTags($post);
    if ($tags !== []) {
        $card .= '<div class="tags">' . implode('', array_map(
            fn ($t) => '<a class="tag" href="/tag/' . e($t) . '">' . e($t) . '</a>',
            $tags
        )) . '</div>';
    }
    $card .= '</div></article>' . "\n";

    return $card;
}

// ---------- routing ----------
$route = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$route = rtrim($route, '/') ?: '/';

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

// ---------- home: intro + featured + latest ----------
if ($route === '/') {
    $posts = $store->posts();
    $pinned = array_values(array_filter($posts, fn ($p) => !empty($p['pinned'])));
    $latest = array_values(array_filter($posts, fn ($p) => empty($p['pinned'])));

    $cats = '';
    foreach ($store->categories() as $slug => $cat) {
        $count = count($store->posts($slug));
        $cats .= '<a href="/category/' . e($slug) . '">' . e($cat['title']) . '</a><small>' . $count . '</small>' . "\n";
    }

    ob_start();
    ?>
<section class="hero hero-full">
  <div class="hero-inner">
    <h1><?= e(blog_title()) ?></h1>
    <p class="tagline"><?= e(blog_tagline()) ?></p>
  </div>
</section>

<section class="intro">
  <h2>How this started</h2>
  <p>This blog begins in a <a href="https://hermes-agent.nousresearch.com/docs/user-guide/features/kanban">Telegram conversation</a>: one developer and one AI agent, talking about how innovation is mostly <em>recombination</em> — taking existing ideas from unrelated places and combining them well. The space of combinations is effectively infinite, so the scarce skill isn't inventing, it's <em>selecting what to combine</em>.</p>
  <p>The idea became a machine: a <strong>kanban board of specialist AI agents</strong> on Hermes — scouts that mine different knowledge surfaces, a combinator that fuses them using named moves (cross-domain, temporal, inversion, removal), a critic that kills weak ideas cheaply before they're built, a director that selects and mutates failures into new material, and a builder and shipper that turn survivors into working, tested software.</p>
  <p>Every concept carries its own kill condition. Every build is the smallest experiment capable of <em>disproving</em> it. Negative results get published as faithfully as positive ones.</p>
  <p>The first challenge card went on the board, and the agents took it from there — you can read <a href="/post/hello-lab">what happened</a>, or browse what they shipped.</p>
</section>

<?php if ($pinned !== []): ?>
<section class="home-section">
  <h2>Featured</h2>
  <div class="post-list"><?php foreach ($pinned as $p) {
        echo post_card($p, $store);
    } ?></div>
</section>
<?php endif; ?>

<section class="home-section">
  <h2>Latest posts</h2>
  <nav class="cats"><?= $cats ?></nav>
  <div class="post-list"><?php
    if ($latest === []) {
        echo '<p class="muted">No posts yet — the agents are still working.</p>';
    }
    foreach ($latest as $p) {
        echo post_card($p, $store);
    } ?></div>
</section>
    <?php
    $body = (string) ob_get_clean();

    echo page('A multi-agent innovation lab, in public', $body);
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
    if (!empty($post['image'])) {
        $body .= '<div class="post-hero"><img src="/content/' . e($post['image']) . '" alt=""></div>' . "\n";
    }
    $body .= '<h1>' . e($post['title'] ?? $m[1]) . '</h1>' . "\n";
    $body .= '<div class="meta">' . e($post['date'] ?? '');
    if (!empty($post['category']) && $store->category($post['category'])) {
        $body .= ' · <a href="/category/' . e($post['category']) . '">' . e($store->category($post['category'])['title']) . '</a>';
    }
    if (!empty($post['author']) && $store->author($post['author'])) {
        $a = $store->author($post['author']);
        $body .= ' · <span class="author">' . e($a['name'] ?? $post['author']) . '</span>';
    }
    $body .= "</div>\n";
    foreach ($store->postTags($post) as $tag) {
        $body .= '<a class="tag" href="/tag/' . e($tag) . '">' . e($tag) . '</a>' . " ";
    }
    $body = rtrim($body) . "\n";
    $body .= BlogMarkdown::render($post['body']) . "\n";
    $body .= "</article>\n";
    $body .= '<div class="eng-zone" data-post="' . e($post['slug']) . '"></div>' . "\n";
    $body .= "<p><a href=\"/\">← All posts</a></p>";

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
        $cards .= post_card($post, $store);
    }

    $body .= $cards !== '' ? "<div class=\"post-list\">\n{$cards}</div>" : '<p class="muted">No posts yet.</p>';
    $body .= "\n<p><a href=\"/\">← All posts</a></p>";

    echo page($cat['title'], $body);
    exit;
}

// ---------- privacy / cookies ----------
if (preg_match('#^/(privacy|cookies)$#', $route, $m)) {
    $file = CONTENT_ROOT . '/' . ($m[1] === 'privacy' ? 'privacy' : 'cookies') . '.md';
    $md = is_file($file) ? (string) file_get_contents($file) : 'Policy page missing.';
    $title = $m[1] === 'privacy' ? 'Privacy Policy' : 'Cookie Policy';
    echo page($title, '<article class="post policy">' . BlogMarkdown::render($md) . '</article>');
    exit;
}

// ---------- tag ----------
if (preg_match('#^/tag/([a-z0-9-]+)$#', $route, $m)) {
    $posts = $store->postsByTag($m[1]);

    if ($posts === []) {
        http_response_code(404);
        echo page('Not found', '<h1>404</h1><p>No posts with this tag.</p>');
        exit;
    }

    $cards = '';
    foreach ($posts as $post) {
        $cards .= post_card($post, $store);
    }

    $body = '<h1>#' . e($m[1]) . '</h1>' . "\n"
        . '<p class="muted">' . count($posts) . ' post(s)</p>' . "\n"
        . "<div class=\"post-list\">\n{$cards}</div>\n"
        . '<p><a href="/">← All posts</a></p>';

    echo page('#' . $m[1], $body);
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