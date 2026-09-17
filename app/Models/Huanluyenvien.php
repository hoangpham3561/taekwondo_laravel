<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuanLuyenVien extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'huan_luyen_vien';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cap_dai',
        'ho_va_ten',
        'ngay_thang_nam_sinh',
        'ma_clb',
        'ma_don_vi',
        'quyen_so',
        'address',
        'phone',
        'email',
        'password',
        'role',
        'experience_years',
        'specialization',
        'bio',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
      
    ];

    /**
     * Get cap dai ordered by sequence
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('id', 'asc');
    }
}