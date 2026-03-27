<?php

namespace Modules\Analytics\Models;

use App\Enums\RiskLevel;
use App\Enums\TrafficType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassifiedVisitLog extends Model
{
    protected $table = 'classified_visit_logs';

    protected $fillable = [
        'raw_log_id',
        'ip_address',
        'traffic_type',
        'suspicion_reason',
        'bot_name',
        'risk_level',
        'matched_rule',
        'visited_at',
    ];

    protected $casts = [
        'traffic_type' => TrafficType::class,
        'risk_level' => RiskLevel::class,
        'visited_at' => 'datetime',
    ];

    public function rawLog(): BelongsTo
    {
        return $this->belongsTo(RawRequestLog::class, 'raw_log_id');
    }
}
