<?php
// Scheduled posts: future front-matter dates stay hidden (list + direct URL)
function test_future_dated_post_hidden_from_lists_and_direct_url(T $t): void
{
    $root = sys_get_temp_dir() . '/labblog_sched_' . getmypid();
    is_dir($root . '/posts') || mkdir($root . '/posts', 0777, true);
    $tomorrow = (new DateTimeImmutable('tomorrow UTC'))->format('Y-m-d');
    file_put_contents($root . '/posts/future-post.md',
        "---\ntitle: Scheduled\ndate: {$tomorrow}\ncategory: news\n---\nHidden until {$tomorrow}.");
    file_put_contents($root . '/posts/live-post.md',
        "---\ntitle: Live\ndate: 2020-01-01\ncategory: news\n---\nVisible.");
    $store = new ContentStore($root);

    $slugs = array_column($store->posts(), 'slug');
    $t->assertSame(false, in_array('future-post', $slugs, true), 'future post hidden from lists');
    $t->assertSame(true, in_array('live-post', $slugs, true), 'live post listed');

    $t->assertSame(null, $store->post('future-post'), 'future post 404s on direct URL');
    $t->assertSame('Live', $store->post('live-post')['title'] ?? null, 'live post loads');

    // today-boundary: a post dated TODAY is public (compare is strict >)
    $today = (new DateTimeImmutable('today UTC'))->format('Y-m-d');
    file_put_contents($root . '/posts/today-post.md',
        "---\ntitle: Today\ndate: {$today}\ncategory: news\n---\nVisible today.");
    $slugs2 = array_column($store->posts(), 'slug');
    $t->assertSame(true, in_array('today-post', $slugs2, true), 'same-day post is public');

    foreach (glob($root . '/posts/*.md') ?: [] as $f) { unlink($f); }
    rmdir($root . '/posts'); rmdir($root);
}
