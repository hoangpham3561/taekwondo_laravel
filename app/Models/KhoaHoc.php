<?php
// app/Models/KhoaHoc.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhoaHoc extends Model
{
    use HasFactory;

    protected $table = 'khoa_hoc';
    
    protected $fillable = [
        'title', 'description', 'level', 'quarter', 'year',
        'coach_id', 'club_id', 'branch_id', 'start_date', 
        'end_date', 'current_students', 'image_url', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'current_students' => 'integer',
        'is_active' => 'boolean'
    ];

    // ✅ Relationships từ foreign keys
    public function coach()
    {
        return $this->belongsTo(HuanLuyenVien::class, 'coach_id');
    }

    public function club()
    {
        return $this->belongsTo(CauLacBo::class, 'club_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);  // Giả sử có model Branch
    }

    // Scope hữu ích
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('year', date('Y'));
    }
}
