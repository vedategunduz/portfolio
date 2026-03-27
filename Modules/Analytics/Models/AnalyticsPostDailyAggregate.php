<?php

namespace Modules\Analytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Blog\Models\Post;

class AnalyticsPostDailyAggregate extends Model
{
    protected $table = 'analytics_post_daily_aggregates';

    protected $fillable = [
        'date',
        'post_id',
        'total_views',
        'unique_visitors',
        'avg_total_time_seconds',
        'avg_active_time_seconds',
        'avg_scroll_percent',
        'completed_read_count',
        'engaged_read_count',
        'bounce_count',
        'returning_visitor_count',
        'bot_views',
        'suspicious_views',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
