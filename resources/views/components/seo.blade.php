@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'url' => null,
    'type' => null,
    'author' => null,
    'robots' => null,
    'canonical' => true,
])

@php
    $seoTitle = $title ?? config('seo.default.title');
    $seoDescription = $description ?? config('seo.default.description');
    $seoKeywords = $keywords ?? config('seo.default.keywords');
    $seoImage = $image ?? config('seo.default.image');
    $seoUrl = $url ?? config('seo.default.url');
    $seoType = $type ?? config('seo.og_type');
    $seoAuthor = $author ?? config('seo.default.author');
    $seoRobots = $robots ?? config('seo.robots');
    $seoCanonical = $canonical && config('seo.canonical');
    $currentUrl = request()->url();
@endphp

<!-- Primary Meta Tags -->
<meta name="title" content="{{ $seoTitle }}">
<meta name="description" content="{{ $seoDescription }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="author" content="{{ $seoAuthor }}">
<meta name="robots" content="{{ $seoRobots }}">

@if($seoCanonical)
    <link rel="canonical" href="{{ $url ?? $currentUrl }}">
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:url" content="{{ $url ?? $currentUrl }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Twitter -->
<meta property="twitter:card" content="{{ config('seo.twitter_card') }}">
<meta property="twitter:url" content="{{ $url ?? $currentUrl }}">
<meta property="twitter:title" content="{{ $seoTitle }}">
<meta property="twitter:description" content="{{ $seoDescription }}">
<meta property="twitter:image" content="{{ $seoImage }}">
@if(config('seo.social.twitter'))
    <meta property="twitter:site" content="{{ config('seo.social.twitter') }}">
    <meta property="twitter:creator" content="{{ config('seo.social.twitter') }}">
@endif

<!-- Additional SEO Tags -->
<meta name="theme-color" content="#D62113">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="format-detection" content="telephone=no">

<!-- JSON-LD Structured Data -->
@php
    $sameAs = [];
    if(config('seo.social.github')) $sameAs[] = config('seo.social.github');
    if(config('seo.social.linkedin')) $sameAs[] = config('seo.social.linkedin');
    if(config('seo.social.instagram')) $sameAs[] = config('seo.social.instagram');

    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $seoAuthor,
        'url' => $seoUrl,
        'image' => $seoImage,
        'description' => $seoDescription,
        'jobTitle' => 'Full-Stack Developer',
        'knowsAbout' => ['Laravel', 'PHP', 'JavaScript', 'RESTful API', 'JWT Authentication', 'MySQL', 'Next.js', 'Tailwind CSS'],
        'sameAs' => $sameAs,
    ];

    if(config('seo.social.email')) {
        $structuredData['email'] = config('seo.social.email');
    }
@endphp
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

