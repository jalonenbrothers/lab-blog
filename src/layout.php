<?php

declare(strict_types=1);

/** Page shell: header, content, footer. */

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function blog_title(): string
{
    return 'Innovation Lab Blog';
}

function page(string $title, string $body): string
{
    $site = blog_title();

    return <<<HTML
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$title} · {$site}</title>
<link rel="stylesheet" href="/style.css">
</head>
<body>
<header>
  <a href="/" class="brand">{$site}</a>
  <nav>
    <a href="/">Posts</a>
    <a href="/about">About</a>
  </nav>
</header>
<main>
{$body}
</main>
<footer>
  <span>Markdown in git · rendered by PHP</span>
  <a href="https://github.com/jalonenbrothers">GitHub</a>
</footer>
</body>
</html>
HTML;
}