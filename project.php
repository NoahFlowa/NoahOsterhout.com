<?php

declare(strict_types=1);

require __DIR__ . '/lib/site.php';

$slug = current_project_slug();
if ($slug === null) {
    render_not_found();
}

$canonicalSlug = strtolower($slug);
$path = project_markdown_path($canonicalSlug);
if ($path === null) {
    render_not_found();
}

if ($slug !== $canonicalSlug) {
    https_redirect('/projects/' . $canonicalSlug);
}

$doc = load_markdown($path);
$meta = $doc['meta'];
$title = trim((string) ($meta['title'] ?? ''));
if ($title === '') {
    $title = $canonicalSlug;
}
$description = trim((string) ($meta['description'] ?? ''));

render_page([
    'title' => $title . ' — Noah Osterhout',
    'description' => $description,
    'canonical' => SITE_ORIGIN . '/projects/' . $canonicalSlug,
    'og_type' => 'article',
    'main_class' => 'doc',
    'kicker' => true,
    'body' => $doc['html'],
]);
