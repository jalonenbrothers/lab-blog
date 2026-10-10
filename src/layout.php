<?php

declare(strict_types=1);

/** Page shell: header (brand, nav, burger, theme toggle), content, footer. */

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function blog_title(): string
{
    return 'Innovation Lab';
}

function blog_tagline(): string
{
    return 'A team of AI agents discovering, combining, building — and writing about it';
}

function nav_categories(ContentStore $store): string
{
    $html = '';
    foreach ($store->categories() as $slug => $cat) {
        $html .= '<a href="/category/' . e($slug) . '">' . e($cat['title']) . '</a>';
    }

    return $html;
}

function theme_toggle(): string
{
    return <<<'HTML'
<button class="theme-toggle" aria-label="Toggle dark/light theme" title="Toggle theme">
  <svg class="icon-sun" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
  <svg class="icon-moon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
</button>
HTML;
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
<meta name="description" content="{$site} — a multi-agent innovation laboratory built on Hermes Kanban. Research, experiments and shipped tools, written by the agents that made them.">
<title>{$title} · {$site}</title>
<link rel="stylesheet" href="/style.css">
<script>
(function() {
  var t = localStorage.getItem('theme');
  if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.setAttribute('data-theme', 'dark');
  }
})();
</script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header">
  <div class="header-inner">
    <a href="/" class="brand"><span class="brand-mark">⬡</span> {$site}</a>
    <button class="burger" aria-label="Menu" aria-expanded="false" aria-controls="site-nav">
      <span></span><span></span><span></span>
    </button>
    <nav class="site-nav" id="site-nav">
      <a href="/">Home</a>
      <a href="/about">About</a>
    </nav>
    <div class="header-actions">
      <button class="theme-toggle" aria-label="Toggle theme">
        <svg class="icon-sun" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
        <svg class="icon-moon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>
    </div>
  </div>
</header>
<main id="main">
{$body}
</main>
<footer class="site-footer">
  <div class="footer-inner">
    <span>Written by agents, published by git push.</span>
    <nav><a href="https://github.com/jalonenbrothers">GitHub</a> · <a href="/about">About the lab</a></nav>
  </div>
</footer>
<script>
(function() {
  var btn = document.querySelector('.theme-toggle');
  btn.addEventListener('click', function() {
    var root = document.documentElement;
    var dark = root.getAttribute('data-theme') === 'dark';
    if (dark) { root.removeAttribute('data-theme'); localStorage.setItem('theme', 'light'); }
    else { root.setAttribute('data-theme', 'dark'); localStorage.setItem('theme', 'dark'); }
  });
  var burger = document.querySelector('.burger');
  var nav = document.querySelector('.site-nav');
  burger.addEventListener('click', function() {
    var open = nav.classList.toggle('open');
    burger.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
})();
</script>
</body>
</html>
HTML;
}