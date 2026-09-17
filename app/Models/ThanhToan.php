<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThanhToan extends Model
{
    protected $table = 'thanh_toan';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_date',
        'month',
        'year',
        'status',
        'note',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(VoSinh::class, 'user_id');
    }
}
