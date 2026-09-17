<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapDai extends Model
{
    protected $table = 'cap_dai';
    
    protected $fillable = ['name', 'color', 'order_sequence', 'description', 'images', 'belt_description', 'minimum_time_months', 'minimum_age', 'age_requirement_note', 'required_poomsae_code', 'required_poomsae_name'];

    protected $casts = [
        'images' => 'array', 
        'order_sequence' => 'integer',
        'minimum_time_months' => 'integer',
        'minimum_age' => 'integer',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_sequence', 'asc');
    }

    // ✅ Fix: Bỏ withTimestamps()
    public function baiQuyen()
    {
        return $this->belongsToMany(BaiQuyen::class, 'cap_dai_bai_quyen')
                    ->withPivot('loai_quyen', 'thu_tu_uu_tien')
                    ->orderByPivot('thu_tu_uu_tien');
    }

    public function baiQuyenBatBuoc()
    {
        return $this->baiQuyen()->wherePivot('loai_quyen', 'bat_buoc')->first();
    }
}

