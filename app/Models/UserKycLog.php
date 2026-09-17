<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKycLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_kyc_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'verify_token',
        'status',
        'name',
        'identity_card',
        'identity_card_img',
        'identity_card_back_img',
        'identity_card_selfie_img',
        'verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
