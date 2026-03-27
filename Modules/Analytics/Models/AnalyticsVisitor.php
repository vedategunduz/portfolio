<?php

namespace Modules\Analytics\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalyticsVisitor extends Model
{
    protected $table = 'analytics_visitors';

    protected $fillable = [
        'visitor_uuid',
        'user_id',
        'first_seen_at',
        'last_seen_at',
        'is_bot',
        'is_suspicious',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'is_bot' => 'boolean',
        'is_suspicious' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(AnalyticsSession::class, 'visitor_id');
    }

    public function postViews(): HasMany
    {
        return $this->hasMany(AnalyticsPostView::class, 'visitor_id');
    }
}
