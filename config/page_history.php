<?php

/**
 * Sayfa geçmişi (LogPageHistory) loglama ve sınıflandırma ayarları.
 * Pattern ve kurallar buradan yönetilir; yeni kural eklemek için sadece config güncellenir.
 */
return [
    'enabled' => env('PAGE_HISTORY_ENABLED', true),

    /*
    | Middleware'in atlayacağı path'ler (prefix). admin/* zaten middleware içinde atlanıyor.
    | livewire: Livewire AJAX istekleri (filtreleme, pagination, modal vb.) loglanmaz.
    */
    'skip_paths' => [
        '/up',
        '/horizon',
        '/telescope',
        'livewire',
    ],

    /*
    | Şüpheli sayılan path pattern'leri (regex). Eşleşen istekler suspicious_bot + exploit event üretir.
    */
    'suspicious_path_patterns' => [
        '#^/\.env$#i',
        '#/wp-admin#i',
        '#/wp-login\.php#i',
        '#/phpmyadmin#i',
        '#/vendor/#i',
        '#/storage/logs#i',
        '#/index\.php\?.*phpinfo#i',
        '#think\\\\app#i',
        '#phpunit#i',
        '#/actuator#i',
        '#/shell\.php#i',
        '#/eval#i',
        '#base64_decode#i',
        '#call_user_func#i',
        '#\.git/#i',
        '#/\.aws/#i',
        '#/config\.php#i',
        '#/debug#i',
        '#/\.env\.#i',
        '#/backup#i',
        '#/adminer#i',
        '#/mysql#i',
        '#/\.svn/#i',
    ],

    /*
    | Query string veya path'te aranacak exploit çağrışımlı kelime pattern'leri (case-insensitive).
    */
    'suspicious_query_patterns' => [
        'phpinfo',
        'think\\app',
        'phpunit',
        'shell',
        'eval(',
        'base64_decode',
        'call_user_func_array',
        'passthru',
        'system(',
        'exec(',
        'assert(',
        'create_function',
        'preg_replace.*/e',
        'file_get_contents.*http',
        'include.*http',
        'union.*select',
        'concat(',
        '0x',
        '../',
        '..%2f',
    ],

    /*
    | Bilinen iyi niyetli bot UA parçaları → known_bot.
    */
    'known_bot_signatures' => [
        'Googlebot',
        'Bingbot',
        'Slurp',
        'DuckDuckBot',
        'Baiduspider',
        'YandexBot',
        'facebookexternalhit',
        'Twitterbot',
        'LinkedInBot',
        'Slackbot',
        'Discordbot',
        'WhatsApp',
        'TelegramBot',
        'Applebot',
        'PetalBot',
        'AhrefsBot',
        'SemrushBot',
        'MJ12bot',
        'DotBot',
        'Bytespider',
    ],

    /*
    | Monitoring / health check path'leri → traffic_type: monitoring.
    */
    'monitoring_paths' => [
        '/up',
        '/health',
        '/ping',
        '/ready',
        '/live',
    ],

    /*
    | Internal IP'ler (sunucu / load balancer) → traffic_type: internal.
    */
    'internal_ips' => [
        '127.0.0.1',
        '::1',
    ],

    /*
    | Asset uzantıları: bu path'lerde biten istekler is_asset_request = true.
    */
    'asset_extensions' => [
        'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot', 'map',
    ],

    /*
    | Aynı IP için kısa sürede aşılınca şüpheli sayılacak istek sayısı.
    */
    'rate_limit_window_seconds' => 10,
    'rate_limit_max_requests' => 30,

    'robots_path' => '/robots.txt',

    /*
    | Log retention (gün). 0 = silme.
    */
    'raw_retention_days' => (int) env('PAGE_HISTORY_RAW_RETENTION_DAYS', 30),
    'classified_retention_days' => (int) env('PAGE_HISTORY_CLASSIFIED_RETENTION_DAYS', 90),
    'exploit_retention_days' => (int) env('PAGE_HISTORY_EXPLOIT_RETENTION_DAYS', 365),

    'queue_logging' => env('PAGE_HISTORY_QUEUE_LOGGING', false),
    'queue_connection' => env('PAGE_HISTORY_QUEUE_CONNECTION', null),
];
