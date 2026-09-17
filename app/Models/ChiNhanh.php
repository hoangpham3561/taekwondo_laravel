<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiNhanh extends Model
{
    protected $table = 'chi_nhanh';

    protected $fillable = ['club_id', 'branch_code', 'name', 'address', 'phone', 'email', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(CauLacBo::class, 'club_id');
    }
}
