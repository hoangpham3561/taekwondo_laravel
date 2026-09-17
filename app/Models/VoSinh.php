<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class VoSinh extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vo_sinh';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ho_va_ten',
        'ngay_thang_nam_sinh',
        'ma_hoi_vien',
        'ma_clb',
        'ma_don_vi',
        'quyen_so',
        'cap_dai_id',
        'gioi_tinh',
        'email',
        'phone',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'active_status',
        'profile_image_url',
        'images',
        'password',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ngay_thang_nam_sinh' => 'date',
        'active_status' => 'boolean',
        'images' => 'array',
        'quyen_so' => 'integer',
    ];

    /**
     * Relationship with cap_dai
     */
    public function capDai()
    {
        return $this->belongsTo(CapDai::class, 'cap_dai_id');
    }
}