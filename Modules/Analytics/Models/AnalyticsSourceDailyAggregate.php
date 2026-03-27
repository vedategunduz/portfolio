<?php

namespace Modules\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsSourceDailyAggregate extends Model
{
    protected $table = 'analytics_source_daily_aggregates';

    protected $fillable = [
        'date',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'referrer_domain',
        'views',
        'unique_visitors',
        'engaged_reads',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
