<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaiQuyen extends Model
{
    protected $table = 'bai_quyen';
    
    protected $fillable = [
        'mo_ta', 
        'so_dong_tac', 
        'thoi_gian_thuc_hien', 
        'khoi_luong_ly_thuyet',
        'ten_bai_quyen_vietnamese', 
        'ten_bai_quyen_english',
        'ten_bai_quyen_korean', 
        'cap_do'
    ];

    // Relationship ngược lại
    public function capDai()
    {
        return $this->belongsToMany(CapDai::class, 'cap_dai_bai_quyen');
    }
}
