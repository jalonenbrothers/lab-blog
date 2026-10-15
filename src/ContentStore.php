<?php

declare(strict_types=1);

/**
 * Front-matter loader (posts, categories, authors) with pinned/featured
 * support and author resolution.
 *
 * Post file format (content/posts/<slug>.md):
 * ---
 * title: My Post
 * category: announcements
 * date: 2026-09-14
 * author: hermes      (optional; "hermes" | "ossi" | a defined persona)
 * pinned: true        (optional; featured on the home page)
 * summary: ...
 * image: images/hello.jpg
 * ---
 */
final class ContentStore
{
    public function __construct(private readonly string $root)
    {
    }

    /** @return list<array<string,mixed>> posts, pinned first, then newest date first */
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

        usort($posts, function ($a, $b) {
            $pa = ($a['pinned'] ?? false) ? 1 : 0;
            $pb = ($b['pinned'] ?? false) ? 1 : 0;
            if ($pa !== $pb) {
                return $pb <=> $pa;
            }

            return strcmp((string) $b['date'], (string) $a['date']);
        });

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

    /** @return array<string,int> tag => post count */
    public function tags(): array
    {
        $counts = [];
        foreach ($this->posts() as $post) {
            foreach ($this->postTags($post) as $tag) {
                $counts[$tag] = ($counts[$tag] ?? 0) + 1;
            }
        }
        ksort($counts);

        return $counts;
    }

    /** @return list<string> */
    public function postTags(array $post): array
    {
        $raw = (string) ($post['tags'] ?? '');
        if ($raw === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($t) => trim(strtolower($t)),
            explode(',', $raw)
        )));
    }

    /** @return list<array<string,mixed>> posts carrying a tag */
    public function postsByTag(string $tag): array
    {
        $tag = strtolower(trim($tag));

        return array_values(array_filter(
            $this->posts(),
            fn ($p) => in_array($tag, $this->postTags($p), true)
        ));
    }

    public function category(string $slug): ?array
    {
        $path = $this->root . '/categories/' . $slug . '.md';

        return is_file($path) ? $this->loadFrontMatter($path) : null;
    }

    /** @return array<string,array<string,string>> key => {name, role, avatar} */
    public function authors(): array
    {
        $authors = [];
        foreach (glob($this->root . '/authors/*.md') ?: [] as $file) {
            $a = $this->loadFrontMatter($file);
            if ($a !== null) {
                $authors[basename($file, '.md')] = $a + ['avatar' => 'avatars/' . basename($file) . '.png'];
            }
        }

        return $authors;
    }

    public function author(string $key): ?array
    {
        $path = $this->root . '/authors/' . $key . '.md';

        return is_file($path) ? ($this->loadFrontMatter($path) + ['avatar' => 'avatars/' . $key . '.png']) : null;
    }

    /** Path to an image inside content/, or null if it escapes the root. */
    public function imagePath(string $relative): ?string
    {
        $full = realpath($this->root . '/' . ltrim($relative, '/'));

        if ($full === false || !str_starts_with($full, (string) realpath($this->root) . '/')) {
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

        $meta['pinned'] = (($meta['pinned'] ?? 'false') === 'true');

        return array_merge($meta, [
            'slug' => basename($file, '.md'),
            'body' => $this->body($file),
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