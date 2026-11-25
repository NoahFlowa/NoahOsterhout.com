<?php

declare(strict_types=1);

require __DIR__ . '/lib/site.php';

$doc = load_markdown(__DIR__ . '/content/resume.md');
$meta = $doc['meta'];
$name = (string) ($meta['name'] ?? 'Noah Osterhout');
$role = (string) ($meta['role'] ?? 'Software Developer');

render_page([
    'title' => $name . ' — ' . $role,
    'description' => (string) ($meta['description'] ?? ''),
    'canonical' => SITE_ORIGIN . '/',
    'og_type' => 'profile',
    'main_class' => 'resume',
    'header' => site_header($meta),
    'jsonld' => person_jsonld($meta),
    'body' => $doc['html'],
]);
