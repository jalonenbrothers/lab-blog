<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/BlogMarkdown.php';

function test_md_heading_para_list(T $t): void
{
    $t->assertSame('<h2>Hi</h2>', BlogMarkdown::render('## Hi'));
    $t->assertSame('<p>plain</p>', BlogMarkdown::render('plain'));
    $t->assertSame("<ul>\n<li>a</li>\n<li>b</li>\n</ul>", BlogMarkdown::render('- a' . "\n" . '- b'));
}

function test_md_inline(T $t): void
{
    $t->assertSame('<p><code>x</code> <strong>b</strong> <em>i</em></p>', BlogMarkdown::render('`x` **b** *i*'));
    $t->assertSame('<p><a href="https://a.b">l</a></p>', BlogMarkdown::render('[l](https://a.b)'));
}

function test_md_image_relative_prefix(T $t): void
{
    $html = BlogMarkdown::render('![shot](images/shot.jpg)');
    $t->assertSame('<p><img src="/content/images/shot.jpg" alt="shot" loading="lazy"></p>', $html);
}

function test_md_image_absolute_untouched(T $t): void
{
    $html = BlogMarkdown::render('![x](https://cdn.example/x.png)');
    $t->assertMatches('|https://cdn.example/x.png|', $html, 'absolute stays');
}

function test_md_escapes_html(T $t): void
{
    $html = BlogMarkdown::render('<script>alert(1)</script>');
    $t->assertMatches('|&lt;script&gt;|', $html, 'escaped');
    $t->assertSame(0, preg_match('|<script>|', $html), 'no raw script tag');
}

function test_md_code_fence(T $t): void
{
    $html = BlogMarkdown::render("```bash\necho hi\n```");
    $t->assertSame('<pre><code class="language-bash">echo hi</code></pre>', $html);
}
function test_markdown_renders_table(T $t): void
{
    $md = "| A | B |\n|---|---:|\n| x | 1 |\n| y | 22 |";
    $html = BlogMarkdown::render($md);
    $t->assertMatches('/<table>/', $html, 'table element');
    $t->assertMatches('/<thead>/', $html, 'thead');
    $t->assertMatches('/<th[^>]*>/', $html, 'th');
    $t->assertMatches('/text-align: right/', $html, 'align right from delimiter');
    $t->assertMatches('/<td[^>]*>22<\/td>/', $html, 'body cell');
    $t->assertMatches('/^(?!.*<p>\|)/s', $html, 'no raw pipes in p');
}
