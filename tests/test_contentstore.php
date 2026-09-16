<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/ContentStore.php';

function make_store(T $t): ContentStore
{
    $dir = sys_get_temp_dir() . '/blog-test-' . uniqid();
    mkdir($dir . '/posts', 0777, true);
    mkdir($dir . '/categories', 0777, true);

    file_put_contents($dir . '/posts/older.md', "---\ntitle: Older\ncategory: news\ndate: 2026-09-01\n---\nOld.");
    file_put_contents($dir . '/posts/newer.md', "---\ntitle: Newer\ncategory: lab\ndate: 2026-09-10\nsummary: newest\n---\nNew.");
    file_put_contents($dir . '/posts/broken.md', "no front matter here");

    file_put_contents($dir . '/categories/lab.md', "---\ntitle: Lab Notes\ndescription: from the lab\n---");
    file_put_contents($dir . '/categories/news.md', "---\ntitle: News\n---");

    return new ContentStore($dir);
}

function test_posts_listed_newest_first(T $t): void
{
    $posts = make_store($t)->posts();
    $t->assertSame(['newer', 'older'], array_column($posts, 'slug'), 'newest first');
}

function test_posts_filter_by_category(T $t): void
{
    $posts = make_store($t)->posts('news');
    $t->assertSame(['older'], array_column($posts, 'slug'), 'category filter');
}

function test_post_lookup(T $t): void
{
    $post = make_store($t)->post('newer');
    $t->assertSame('Newer', $post['title']);
    $t->assertSame('newest', $post['summary']);
    $t->assertSame('New.', $post['body']);
    $t->assertSame(null, make_store($t)->post('missing'), 'missing post null');
    $t->assertSame(null, make_store($t)->post('broken'), 'no-front-matter skipped from lookup');
}

function test_categories(T $t): void
{
    $cats = make_store($t)->categories();
    $t->assertSame(['lab', 'news'], array_keys($cats), 'sorted slugs');
    $t->assertSame('Lab Notes', $cats['lab']['title']);
    $t->assertSame(null, make_store($t)->category('missing'));
}

function test_image_path_guards_traversal(T $t): void
{
    $store = make_store($t);
    $root = (string) realpath(sys_get_temp_dir() . '/' . substr((string) $store->post('newer')['slug'], 0, 0));
    $t->assertSame(null, $store->imagePath('../../etc/passwd'), 'traversal blocked');
    $t->assertSame(null, $store->imagePath('images/nope.jpg'), 'missing image null');
}

function test_body_strips_front_matter(T $t): void
{
    $post = make_store($t)->post('older');
    $t->assertSame('Old.', $post['body'], 'body only');
}