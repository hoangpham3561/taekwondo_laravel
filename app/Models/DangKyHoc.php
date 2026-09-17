<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DangKyHoc extends Model
{
    protected $table = 'dang_ky_hoc';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'notes',
        'enrolled_at',
        'approved_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(VoSinh::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(KhoaHoc::class, 'course_id');
    }
}
