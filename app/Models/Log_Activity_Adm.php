<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log_Activity_Adm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_activity_adm';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject',
        'url',
        'method',
        'ip',
        'agent',
        'task',
        'created_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
}

