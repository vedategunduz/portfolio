<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Modules\Analytics\Application\Actions\BuildDailyBlogAnalyticsAggregatesAction;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('analytics:aggregate-daily {date?}', function (?string $date = null) {
    $result = app(BuildDailyBlogAnalyticsAggregatesAction::class)->handle($date);

    $this->info(sprintf(
        'Aggregates built for %s (post: %d, source: %d, device: %d)',
        $result['date'],
        $result['post_rows'],
        $result['source_rows'],
        $result['device_rows']
    ));
})->purpose('Build daily blog analytics aggregates');

Schedule::command('analytics:aggregate-daily')->hourlyAt(7);
