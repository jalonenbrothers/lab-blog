# lab-blog

The Innovation Lab's blog: markdown files in git, rendered by a tiny
dependency-free PHP app. Same deploy model as the project tracker —
`git push` is publishing.

## Adding a post

Create `content/posts/<slug>.md`:

```markdown
---
title: My Post Title
category: lab          (must match a category file slug)
date: 2026-09-14
summary: One-line card description (optional)
image: images/foo.png  (optional; card header image)
---

Markdown body. Relative image paths get the /content/ prefix
automatically: ![alt](images/foo.png) — put files in
content/images/.
```

## Adding a category

Create `content/categories/<slug>.md`:

```markdown
---
title: Category Name
description: Shown on the category page (optional)
---
```

## Layout

```
index.php            entry point + routing (/, /post/<slug>,
                     /category/<slug>, /about, /content/images/*)
src/ContentStore.php front-matter MD loader (posts, categories,
                     images with traversal guard)
src/BlogMarkdown.php renderer (headings, lists, code fences, inline,
                     images with relative-path rewriting; escapes HTML)
src/layout.php       page shell (header/footer)
style.css            styling
content/posts/       one MD file per post
content/categories/  one MD file per category
content/images/      images
content/about.md     the About page
tests/               zero-dependency runner: php tests/run.php
```

## Running

Dev: `php -S 0.0.0.0:8743 index.php`
Production: point the Hostinger public_html auto-deploy at this repo
(flat root, same as the tracker).

## Tests

```bash
php tests/run.php
```