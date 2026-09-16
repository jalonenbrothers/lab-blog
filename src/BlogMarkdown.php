<?php

declare(strict_types=1);

/**
 * Markdown -> HTML for the blog subset: headings, paragraphs, lists,
 * code fences, inline code/bold/italic/links, images. Escapes all HTML
 * first (safe), then adds relative-image rewriting (content/ prefix).
 */
final class BlogMarkdown
{
    public static function render(string $text, string $imagePrefix = '/content/'): string
    {
        $text = str_replace("\r\n", "\n", $text);
        $text = trim($text);

        $blocks = preg_split('/\n{2,}/', $text) ?: [];
        $html   = '';

        foreach ($blocks as $block) {
            $html .= self::renderBlock($block, $imagePrefix) . "\n";
        }

        return trim($html);
    }

    private static function renderBlock(string $block, string $imagePrefix): string
    {
        if ($block === '') {
            return '';
        }

        if (preg_match('/^```([^\n]*)\n(.*)$/s', $block, $m)) {
            $lang = htmlspecialchars(trim($m[1]), ENT_QUOTES);
            $code = preg_replace('/\n```\s*$/', '', $m[2]) ?? $m[2];
            $code = htmlspecialchars($code, ENT_QUOTES);

            return "<pre><code" . ($lang !== '' ? " class=\"language-$lang\"" : '') . ">$code</code></pre>";
        }

        if (preg_match('/^(#{1,6})\s+(.+)$/', $block, $m) && !str_contains($block, "\n")) {
            $level = strlen($m[1]);

            return '<h' . $level . '>' . self::renderInline($m[2], $imagePrefix) . '</h' . $level . '>';
        }

        $lines = explode("\n", $block);
        $listLines = 0;
        foreach ($lines as $line) {
            if (preg_match('/^\s*[-*]\s+/', $line)) {
                $listLines++;
            }
        }
        $nonEmpty = array_filter($lines, fn ($l) => trim($l) !== '');

        if ($listLines > 0 && $listLines === count($nonEmpty)) {
            $items = '';
            foreach ($lines as $line) {
                if (preg_match('/^\s*[-*]\s+(.*)$/', $line, $m)) {
                    $items .= '<li>' . self::renderInline($m[1], $imagePrefix) . "</li>\n";
                }
            }

            return "<ul>\n$items</ul>";
        }

        return '<p>' . self::renderInline(implode("\n", $lines), $imagePrefix) . '</p>';
    }

    private static function renderInline(string $text, string $imagePrefix): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // images BEFORE links: ![alt](src)
        $text = preg_replace_callback(
            '/!\[([^\]]*)\]\(([^)]+)\)/',
            function ($m) use ($imagePrefix) {
                $src = $m[2];
                if (!preg_match('#^https?://|^/#', $src)) {
                    $src = rtrim($imagePrefix, '/') . '/' . ltrim($src, '/');
                }

                return '<img src="' . $src . '" alt="' . $m[1] . '" loading="lazy">';
            },
            $text
        ) ?? $text;

        $patterns = [
            '/`([^`]+)`/'                => '<code>$1</code>',
            '/\*\*([^*]+)\*\*/'           => '<strong>$1</strong>',
            '/(?<!\*)\*([^*\s][^*]*)\*/' => '<em>$1</em>',
            '/\[([^\]]+)\]\(([^)]+)\)/'  => '<a href="$2">$1</a>',
        ];

        foreach ($patterns as $regex => $replacement) {
            $text = preg_replace($regex, $replacement, $text) ?? $text;
        }

        return $text;
    }
}