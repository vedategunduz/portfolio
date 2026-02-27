<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SEO Settings
    |--------------------------------------------------------------------------
    |
    | These settings will be used as fallback values when specific page
    | SEO values are not provided.
    |
    */

    'default' => [
        'title' => 'Vedat Egündüz - Full-Stack Developer',
        'description' => 'Backend ağırlıklı çalışan bir full-stack geliştiriciyim. Laravel, PHP, RESTful API, JWT authentication ve modern web teknolojileri konusunda deneyimliyim.',
        'keywords' => 'full-stack developer, backend developer, laravel, php, rest api, jwt, web developer, software engineer',
        'author' => 'Vedat Egündüz',
        'image' => env('APP_URL') . '/images/og-image.jpg',
        'url' => env('APP_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media
    |--------------------------------------------------------------------------
    |
    | Your social media handles for SEO and meta tags purposes.
    |
    */

    'social' => [
        'twitter' => '@vedategunduz',
        'github' => 'https://github.com/vedategunduz',
        'linkedin' => 'https://www.linkedin.com/in/vedategunduz',
        'instagram' => 'https://www.instagram.com/vedategunduz',
        'email' => 'vedat.bilisim@outlook.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Open Graph Type
    |--------------------------------------------------------------------------
    |
    | Default Open Graph type for your pages.
    |
    */

    'og_type' => 'website',

    /*
    |--------------------------------------------------------------------------
    | Twitter Card Type
    |--------------------------------------------------------------------------
    |
    | Default Twitter Card type. Options: summary, summary_large_image, etc.
    |
    */

    'twitter_card' => 'summary_large_image',

    /*
    |--------------------------------------------------------------------------
    | Robots Meta
    |--------------------------------------------------------------------------
    |
    | Default robots meta tag value.
    |
    */

    'robots' => 'index, follow',

    /*
    |--------------------------------------------------------------------------
    | Canonical URL
    |--------------------------------------------------------------------------
    |
    | Whether to automatically add canonical URLs to pages.
    |
    */

    'canonical' => true,
];
