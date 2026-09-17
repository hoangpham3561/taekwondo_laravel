<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransferDeposit extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'transfer_note',
        'status',
        'proof_image',
        'bank_code',
        'bank_name',
        'account_number',
        'account_name',
        'qr_url',
        'bank_txn_ref',
        'paid_at',
        'meta',
    ];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
        'meta' => 'array',
    ];

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'Y' => 'Đã duyệt',
            'C' => 'Đã hủy',
            default => 'Chờ xác nhận',
        };
    }
}
