<?php

namespace Modules\Analytics\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalyticsSession extends Model
{
    protected $table = 'analytics_sessions';

    protected $fillable = [
        'session_id',
        'visitor_id',
        'user_id',
        'started_at',
        'ended_at',
        'landing_url',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'ip_hash',
        'user_agent',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'screen_width',
        'screen_height',
        'viewport_width',
        'viewport_height',
        'load_time_ms',
        'dom_ready_ms',
        'time_to_first_interaction_ms',
        'is_bot',
        'is_suspicious',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
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

    public function postViews(): HasMany
    {
        return $this->hasMany(AnalyticsPostView::class, 'session_ref_id');
    }
}
