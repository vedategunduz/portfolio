<?php

namespace Modules\Analytics\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Blog\Models\Post;

class AnalyticsPostView extends Model
{
    protected $table = 'analytics_post_views';

    protected $fillable = [
        'view_uuid',
        'session_ref_id',
        'visitor_id',
        'user_id',
        'post_id',
        'post_slug',
        'view_started_at',
        'view_ended_at',
        'total_time_seconds',
        'active_time_seconds',
        'heartbeat_count',
        'max_scroll_percent',
        'reading_progress_percent',
        'completed_read',
        'engaged_read',
        'first_scroll_at',
        'reached_25_percent',
        'reached_50_percent',
        'reached_75_percent',
        'reached_90_percent',
        'toc_click_count',
        'internal_link_click_count',
        'external_link_click_count',
        'copy_count',
        'share_click_count',
        'is_bot',
        'is_suspicious',
    ];

    protected $casts = [
        'view_started_at' => 'datetime',
        'view_ended_at' => 'datetime',
        'first_scroll_at' => 'datetime',
        'completed_read' => 'boolean',
        'engaged_read' => 'boolean',
        'reached_25_percent' => 'boolean',
        'reached_50_percent' => 'boolean',
        'reached_75_percent' => 'boolean',
        'reached_90_percent' => 'boolean',
        'is_bot' => 'boolean',
        'is_suspicious' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(AnalyticsSession::class, 'session_ref_id');
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(AnalyticsVisitor::class, 'visitor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(AnalyticsEvent::class, 'post_view_id');
    }
}
