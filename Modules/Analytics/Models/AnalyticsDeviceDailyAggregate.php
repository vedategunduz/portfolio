<?php

namespace Modules\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsDeviceDailyAggregate extends Model
{
    protected $table = 'analytics_device_daily_aggregates';

    protected $fillable = [
        'date',
        'device_type',
        'browser',
        'os',
        'views',
        'unique_visitors',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
