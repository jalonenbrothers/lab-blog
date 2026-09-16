<?php

declare(strict_types=1);

/**
 * Minimal front-matter markdown loader for the blog.
 *
 * Post file format (content/posts/<slug>.md):
 * ---
 * title: My Post
 * category: announcements
 * date: 2026-09-14
 * summary: One-line description (optional)
 * image: images/hello.jpg (optional, relative to content/)
 * ---
 * Markdown body...
 *
 * Category file format (content/categories/<slug>.md):
 * ---
 * title: Announcements
 * description: Optional
 * ---
 */
final class ContentStore
{
    public function __construct(private readonly string $root)
    {
    }

    /** @return list<array<string,mixed>> posts, newest date first */
    public function posts(?string $category = null): array
    {
        $posts = [];
        foreach (glob($this->root . '/posts/*.md') ?: [] as $file) {
            $post = $this->loadPost($file);
            if ($post === null) {
                continue;
            }
            if ($category !== null && ($post['category'] ?? '') !== $category) {
                continue;
            }
            $posts[] = $post;
        }

        usort($posts, fn ($a, $b) => strcmp((string) $b['date'], (string) $a['date']));

        return $posts;
    }

    public function post(string $slug): ?array
    {
        $path = $this->root . '/posts/' . $slug . '.md';

        if (!is_file($path)) {
            return null;
        }

        return $this->loadPost($path);
    }

    /** @return array<string,array<string,string>> slug => meta */
    public function categories(): array
    {
        $cats = [];
        foreach (glob($this->root . '/categories/*.md') ?: [] as $file) {
            $cat = $this->loadFrontMatter($file);
            if ($cat !== null) {
                $cats[basename($file, '.md')] = $cat;
            }
        }
        ksort($cats);

        return $cats;
    }

    public function category(string $slug): ?array
    {
        $path = $this->root . '/categories/' . $slug . '.md';

        return is_file($path) ? $this->loadFrontMatter($path) : null;
    }

    /** Path to an image inside content/, or null if it escapes the root. */
    public function imagePath(string $relative): ?string
    {
        $full = realpath($this->root . '/' . ltrim($relative, '/'));

        if ($full === false || !str_starts_with($full, realpath($this->root) . '/')) {
            return null;
        }

        return $full;
    }

    /** @return array<string,mixed>|null */
    private function loadPost(string $file): ?array
    {
        $meta = $this->loadFrontMatter($file);
        if ($meta === null) {
            return null;
        }

        $body = $this->body($file);

        return array_merge($meta, [
            'slug' => basename($file, '.md'),
            'body' => $body,
        ]);
    }

    /** @return array<string,string>|null */
    private function loadFrontMatter(string $file): ?array
    {
        $text = (string) file_get_contents($file);

        if (!preg_match('/^---\s*\n(.*?)\n---\s*\n?/s', $text, $m)) {
            return null;
        }

        $meta = [];
        foreach (explode("\n", $m[1]) as $line) {
            if (preg_match('/^([a-z_]+):\s*(.*)$/', trim($line), $lm)) {
                $meta[$lm[1]] = trim($lm[2]);
            }
        }

        return $meta;
    }

    private function body(string $file): string
    {
        $text = (string) file_get_contents($file);

        return trim((string) preg_replace('/^---\s*\n.*?\n---\s*\n?/s', '', $text));
    }
}