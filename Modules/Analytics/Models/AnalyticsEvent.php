<?php

namespace Modules\Analytics\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Blog\Models\Post;

class AnalyticsEvent extends Model
{
    protected $table = 'analytics_events';

    protected $fillable = [
        'event_uuid',
        'event_type',
        'occurred_at',
        'received_at',
        'session_id',
        'visitor_id',
        'user_id',
        'post_id',
        'post_view_id',
        'url',
        'referrer',
        'payload_json',
        'is_bot',
        'is_suspicious',
        'ip_hash',
        'user_agent',
        'country',
        'city',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'received_at' => 'datetime',
        'payload_json' => 'array',
        'is_bot' => 'boolean',
        'is_suspicious' => 'boolean',
    ];

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

    public function postView(): BelongsTo
    {
        return $this->belongsTo(AnalyticsPostView::class, 'post_view_id');
    }
}
