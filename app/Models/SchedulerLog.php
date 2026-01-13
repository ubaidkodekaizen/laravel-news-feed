<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchedulerLog extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'scheduler',
        'command',
        'status',
        'result_detail',
        'result_data',
        'records_processed',
        'records_updated',
        'records_failed',
        'error_message',
        'error_trace',
        'execution_time_ms',
        'ran_at',
    ];

    protected $casts = [
        'ran_at' => 'datetime',
        'result_data' => 'array',
        'records_processed' => 'integer',
        'records_updated' => 'integer',
        'records_failed' => 'integer',
        'execution_time_ms' => 'integer',
    ];
}
