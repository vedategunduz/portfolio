<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageHistory extends Model
{
    protected $table = 'page_history';

    protected $fillable = [
        'ip_address',
        'path',
        'method',
        'user_agent',
        'referer',
        'session_id',
    ];

    public $timestamps = true;
}
