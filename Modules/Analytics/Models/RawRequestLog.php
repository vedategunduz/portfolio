<?php

namespace Modules\Analytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RawRequestLog extends Model
{
    protected $table = 'raw_request_logs';

    protected $fillable = [
        'ip_address',
        'method',
        'full_url',
        'path',
        'query_string',
        'user_agent',
        'referer',
        'status_code',
        'response_time_ms',
        'visited_at',
        'is_asset_request',
        'session_id',
        'request_fingerprint',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_asset_request' => 'boolean',
    ];

    public function classifiedVisit(): HasOne
    {
        return $this->hasOne(ClassifiedVisitLog::class, 'raw_log_id');
    }
}
