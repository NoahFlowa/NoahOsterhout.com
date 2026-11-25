<?php

declare(strict_types=1);

require_once __DIR__ . '/Parsedown.php';

const SITE_ORIGIN = 'https://noahosterhout.com';

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function https_url(string $url): ?string
{
    return preg_match('#^https://[^\s<>"]+$#', $url) === 1 ? $url : null;
}

function yaml_scalar(string $value): string
{
    $value = trim($value);
    $length = strlen($value);
    if ($length >= 2) {
        $quote = $value[0];
        if (($quote === '"' || $quote === "'") && $value[$length - 1] === $quote) {
            return substr($value, 1, -1);
        }
    }

    return $value;
}

/**
 * @return array<string, mixed>
 */
function parse_meta(string $block): array
{
    $meta = [];
    $listKey = null;
    $item = null;

    $flush = function () use (&$meta, &$listKey, &$item): void {
        if ($item !== null && $listKey !== null) {
            $meta[$listKey][] = $item;
            $item = null;
        }
    };

    foreach (explode("\n", $block) as $line) {
        if ($line === '' || str_starts_with(ltrim($line), '#')) {
            continue;
        }

        if (preg_match('/^([A-Za-z0-9_]+):\s*(.*)$/', $line, $m) === 1) {
            $flush();
            $key = $m[1];
            $value = trim($m[2]);
            if ($value === '') {
                $meta[$key] = [];
                $listKey = $key;
            } else {
                $meta[$key] = yaml_scalar($value);
                $listKey = null;
            }
            continue;
        }

        if ($listKey !== null && preg_match('/^\s+-\s+([A-Za-z0-9_]+):\s*(.*)$/', $line, $m) === 1) {
            $flush();
            $item = [$m[1] => yaml_scalar($m[2])];
            continue;
        }

        if ($listKey !== null && $item !== null && preg_match('/^\s+([A-Za-z0-9_]+):\s*(.*)$/', $line, $m) === 1) {
            $item[$m[1]] = yaml_scalar($m[2]);
        }
    }

    $flush();

    return $meta;
}

function markdown_parser(): Parsedown
{
    static $parser = null;
    if (!$parser instanceof Parsedown) {
        $parser = new Parsedown();
        $parser->setSafeMode(true);
    }

    return $parser;
}

function slugify(string $text): string
{
    $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');

    return $text !== '' ? $text : 'section';
}

function decorate_headings(string $html): string
{
    $used = [];
    $out = preg_replace_callback(
        '/<h([1-4])>(.*?)<\/h\1>/s',
        function (array $m) use (&$used): string {
            $id = slugify($m[2]);
            if (isset($used[$id])) {
                $used[$id]++;
                $id .= '-' . $used[$id];
            } else {
                $used[$id] = 1;
            }

            return '<h' . $m[1] . ' id="' . $id . '"><span class="md-hash" aria-hidden="true"></span>'
                . $m[2] . '</h' . $m[1] . '>';
        },
        $html
    );

    return $out ?? $html;
}

function mark_meta(string $html): string
{
    $out = preg_replace_callback('/<p>(.*?)<\/p>/s', function (array $m): string {
        $inner = $m[1];
        if (!str_contains($inner, '<code>')) {
            return $m[0];
        }

        $plain = preg_replace('/<code>.*?<\/code>/s', '', $inner) ?? '';
        $plain = preg_replace('/<a\b[^>]*>.*?<\/a>/s', '', $plain) ?? '';
        $plain = preg_replace('/<[^>]+>/', '', $plain) ?? '';
        $plain = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plain = preg_replace('/[\s·,.\-–—|]+/u', '', $plain) ?? '';
        if ($plain === '') {
            return '<p class="meta">' . $inner . '</p>';
        }

        return $m[0];
    }, $html);

    return $out ?? $html;
}

function local_image_size(string $src): ?array
{
    $path = parse_url($src, PHP_URL_PATH);
    if (!is_string($path) || !str_starts_with($path, '/projects/img/')) {
        return null;
    }

    $root = realpath(dirname(__DIR__) . '/projects/img');
    $full = realpath(dirname(__DIR__) . $path);
    if ($root === false || $full === false || !str_starts_with($full, $root . DIRECTORY_SEPARATOR)) {
        return null;
    }

    $info = @getimagesize($full);
    if (!is_array($info)) {
        return null;
    }

    return [(int) $info[0], (int) $info[1]];
}

function enhance_images(string $html): string
{
    $out = preg_replace_callback('/<img\b[^>]*>/', function (array $m): string {
        $tag = $m[0];
        if (preg_match('/\ssrc="([^"]+)"/', $tag, $srcMatch) !== 1) {
            return $tag;
        }

        $extra = '';
        if (!str_contains($tag, ' loading=')) {
            $extra .= ' loading="lazy" decoding="async"';
        }
        if (!str_contains($tag, ' width=')) {
            $src = html_entity_decode($srcMatch[1], ENT_QUOTES, 'UTF-8');
            $dims = local_image_size($src);
            if ($dims !== null) {
                $extra .= ' width="' . $dims[0] . '" height="' . $dims[1] . '"';
            }
        }
        if ($extra === '') {
            return $tag;
        }

        $updated = preg_replace('/<img\b/', '<img' . $extra, $tag, 1);

        return $updated ?? $tag;
    }, $html);

    return $out ?? $html;
}

function structure_sections(string $html): string
{
    $chunks = preg_split('/(?=<h[23]\b)/', $html, -1, PREG_SPLIT_NO_EMPTY);
    if ($chunks === false) {
        return $html;
    }

    $out = '';
    $sectionOpen = false;
    $entryOpen = false;

    $closeEntry = function () use (&$out, &$entryOpen): void {
        if ($entryOpen) {
            $out .= "</article>\n";
            $entryOpen = false;
        }
    };
    $closeSection = function () use (&$out, &$sectionOpen, $closeEntry): void {
        $closeEntry();
        if ($sectionOpen) {
            $out .= "</section>\n";
            $sectionOpen = false;
        }
    };

    foreach ($chunks as $chunk) {
        if (preg_match('/^<h2\b/', $chunk) === 1) {
            $closeSection();
            $class = 'resume-section';
            if (preg_match('/\bid="skills"/', $chunk) === 1) {
                $class .= ' skills';
            }
            $out .= '<section class="' . $class . "\">\n" . $chunk;
            $sectionOpen = true;
            continue;
        }

        if (preg_match('/^<h3\b/', $chunk) === 1) {
            if (!$sectionOpen) {
                $out .= "<section class=\"resume-section\">\n";
                $sectionOpen = true;
            }
            $closeEntry();
            $out .= "<article class=\"entry\">\n" . $chunk;
            $entryOpen = true;
            continue;
        }

        $out .= $chunk;
    }

    $closeSection();

    return $out;
}

function render_markdown(string $markdown): string
{
    $html = markdown_parser()->text($markdown);

    return structure_sections(enhance_images(mark_meta(decorate_headings($html))));
}

/**
 * @return array{meta: array<string, mixed>, html: string}
 */
function load_markdown(string $path): array
{
    $raw = file_get_contents($path);
    if ($raw === false) {
        throw new RuntimeException('Unable to read markdown.');
    }

    $raw = str_replace(["\r\n", "\r"], "\n", $raw);
    if (preg_match('/\A---\n(.*?)\n---\n(.*)\z/s', $raw, $m) === 1) {
        return [
            'meta' => parse_meta($m[1]),
            'html' => render_markdown($m[2]),
        ];
    }

    return [
        'meta' => [],
        'html' => render_markdown($raw),
    ];
}

function project_markdown_path(string $slug): ?string
{
    if (preg_match('/^[a-z0-9-]+$/', $slug) !== 1) {
        return null;
    }

    $root = realpath(__DIR__ . '/../content/projects');
    if ($root === false) {
        return null;
    }

    $candidate = $root . DIRECTORY_SEPARATOR . $slug . '.md';
    if (!is_file($candidate)) {
        return null;
    }

    $real = realpath($candidate);
    if ($real === false || !str_starts_with($real, $root . DIRECTORY_SEPARATOR)) {
        return null;
    }

    return $real;
}

function current_project_slug(): ?string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $path = parse_url(is_string($uri) ? $uri : '', PHP_URL_PATH);
    if (is_string($path) && preg_match('#^/projects/([A-Za-z0-9-]+)/?$#', $path, $m) === 1) {
        return $m[1];
    }

    if (isset($_GET['slug']) && is_string($_GET['slug']) && preg_match('/^[A-Za-z0-9-]+$/', $_GET['slug']) === 1) {
        return $_GET['slug'];
    }

    return null;
}

/**
 * @param array<string, mixed> $meta
 */
function site_header(array $meta): string
{
    $name = e((string) ($meta['name'] ?? 'Noah Osterhout'));
    $role = trim((string) ($meta['role'] ?? ''));
    $tagline = trim((string) ($meta['tagline'] ?? ''));

    $parts = [];
    $links = $meta['links'] ?? [];
    if (is_array($links)) {
        foreach ($links as $link) {
            if (!is_array($link)) {
                continue;
            }
            $label = trim((string) ($link['label'] ?? ''));
            $href = https_url((string) ($link['href'] ?? ''));
            if ($label === '' || $href === null) {
                continue;
            }
            $parts[] = '<a rel="me noreferrer" href="' . e($href) . '">' . e($label) . '</a>';
        }
    }

    $nav = '';
    if ($parts !== []) {
        $joined = '';
        foreach ($parts as $i => $part) {
            if ($i > 0) {
                $joined .= '<span class="dot" aria-hidden="true">·</span>';
            }
            $joined .= $part;
        }
        $nav = '<nav class="links" aria-label="Links">' . $joined . '</nav>';
    }

    $roleHtml = $role !== '' ? '<p class="role">' . e($role) . '</p>' : '';
    $tagHtml = $tagline !== '' ? '<p class="tagline">' . e($tagline) . '</p>' : '';

    return '<header class="site-header"><h1>' . $name . '</h1>' . $roleHtml . $tagHtml . $nav . '</header>';
}

/**
 * @param array<string, mixed> $meta
 * @return array<string, mixed>
 */
function person_jsonld(array $meta): array
{
    $same = [];
    $links = $meta['links'] ?? [];
    if (is_array($links)) {
        foreach ($links as $link) {
            if (!is_array($link)) {
                continue;
            }
            $href = https_url((string) ($link['href'] ?? ''));
            if ($href !== null) {
                $same[] = $href;
            }
        }
    }

    $alumni = [];
    $schools = $meta['alumni'] ?? [];
    if (is_array($schools)) {
        foreach ($schools as $school) {
            if (!is_array($school)) {
                continue;
            }
            $schoolName = trim((string) ($school['name'] ?? ''));
            if ($schoolName === '') {
                continue;
            }
            $entry = [
                '@type' => 'CollegeOrUniversity',
                'name' => $schoolName,
            ];
            $href = https_url((string) ($school['href'] ?? ''));
            if ($href !== null) {
                $entry['url'] = $href;
            }
            $alumni[] = $entry;
        }
    }

    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => (string) ($meta['name'] ?? 'Noah Osterhout'),
        'jobTitle' => (string) ($meta['role'] ?? 'Software Developer'),
        'url' => SITE_ORIGIN . '/',
        'description' => (string) ($meta['description'] ?? ''),
    ];

    if ($same !== []) {
        $data['sameAs'] = $same;
    }

    $employer = trim((string) ($meta['employer'] ?? ''));
    if ($employer !== '') {
        $works = [
            '@type' => 'Organization',
            'name' => $employer,
        ];
        $employerUrl = https_url((string) ($meta['employer_url'] ?? ''));
        if ($employerUrl !== null) {
            $works['url'] = $employerUrl;
        }
        $data['worksFor'] = $works;
    }

    if ($alumni !== []) {
        $data['alumniOf'] = $alumni;
    }

    return $data;
}

/**
 * @param array{
 *   title: string,
 *   description: string,
 *   canonical?: string,
 *   og_type?: string,
 *   robots?: string,
 *   jsonld?: array<string, mixed>,
 *   header?: string,
 *   main_class?: string,
 *   kicker?: bool,
 *   body: string
 * } $page
 */
function render_page(array $page): void
{
    $title = (string) $page['title'];
    $description = (string) $page['description'];
    $canonical = isset($page['canonical']) ? (string) $page['canonical'] : '';
    $ogType = (string) ($page['og_type'] ?? 'website');
    $robots = (string) ($page['robots'] ?? 'index,follow');
    $header = (string) ($page['header'] ?? '');
    $body = (string) $page['body'];
    $mainClass = (($page['main_class'] ?? 'resume') === 'doc') ? 'doc' : 'resume';
    $kicker = (bool) ($page['kicker'] ?? false);

    header('Content-Type: text/html; charset=utf-8');

    $canonicalTags = '';
    if ($canonical !== '') {
        $canonicalTags = '<link rel="canonical" href="' . e($canonical) . '">' . "\n"
            . '        <meta property="og:url" content="' . e($canonical) . '">';
    }

    $jsonld = '';
    if (isset($page['jsonld']) && is_array($page['jsonld'])) {
        $jsonld = '<script type="application/ld+json">'
            . json_encode(
                $page['jsonld'],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_THROW_ON_ERROR
            )
            . '</script>';
    }

    $kickerHtml = $kicker
        ? '<p class="kicker chrome"><a href="/">← Resume</a></p>'
        : '';

    $image = SITE_ORIGIN . '/favicons/android-chrome-512x512.png';

    echo '<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' . e($title) . '</title>
        <meta name="description" content="' . e($description) . '">
        <meta name="author" content="Noah Osterhout">
        <meta name="robots" content="' . e($robots) . '">
        <meta name="theme-color" content="#f7f4ed">
        ' . $canonicalTags . '
        <meta property="og:title" content="' . e($title) . '">
        <meta property="og:description" content="' . e($description) . '">
        <meta property="og:type" content="' . e($ogType) . '">
        <meta property="og:site_name" content="Noah Osterhout">
        <meta property="og:locale" content="en_US">
        <meta property="og:image" content="' . e($image) . '">
        <meta property="og:image:alt" content="Noah Osterhout">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="' . e($title) . '">
        <meta name="twitter:description" content="' . e($description) . '">
        <link rel="preload" href="/fonts/source-serif-4-latin.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="/fonts/ibm-plex-mono-400.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="stylesheet" href="/css/main.css">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" href="/favicons/favicon.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
        <link rel="manifest" href="/favicons/site.webmanifest">
        <script>
            (function () {
                try {
                    var theme = localStorage.getItem("theme");
                    if (theme !== "light" && theme !== "dark") {
                        theme = matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                    }
                    document.documentElement.setAttribute("data-theme", theme);
                    var color = document.querySelector("meta[name=theme-color]");
                    if (color) color.setAttribute("content", theme === "dark" ? "#1c1b19" : "#f7f4ed");
                } catch (e) {}
            })();
        </script>
        ' . $jsonld . '
    </head>
    <body>
        <a class="skip" href="#content">Skip to content</a>
        <div class="page">
            <div class="toolbar chrome" role="group" aria-label="View">
                <button type="button" id="print-button">Print / Save as PDF</button>
                <button type="button" id="theme-toggle" aria-pressed="false" aria-label="Switch to dark theme">Dark</button>
            </div>
            ' . $kickerHtml . '
            ' . $header . '
            <main id="content" class="markdown ' . $mainClass . '" tabindex="-1">
                ' . $body . '
            </main>
        </div>
        <script src="/js/theme.js"></script>
    </body>
</html>
';
}

function render_not_found(): void
{
    http_response_code(404);
    render_page([
        'title' => 'Not found — Noah Osterhout',
        'description' => 'This page is not on Noah Osterhout\'s site.',
        'robots' => 'noindex',
        'main_class' => 'doc',
        'kicker' => true,
        'body' => '<h1 id="not-found"><span class="md-hash" aria-hidden="true"></span>Not found</h1>'
            . '<p>This page is not on the site.</p>'
            . '<p><a href="/">Back to the resume</a></p>',
    ]);
    exit;
}
