<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'chi_nhanh';
    
    protected $fillable = [
        'club_id',
        'branch_code',
        'name',
        'address',
        'phone',
        'email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship with Club
    public function club()
    {
        return $this->belongsTo(CauLacBo::class, 'club_id');
    }

    // Relationship with managers
    public function managers()
    {
        return $this->belongsToMany(Huanluyenvien::class, 'quan_ly_chi_nhanh', 'branch_id', 'manager_id')
                    ->withPivot('role', 'is_active', 'assigned_at')
                    ->wherePivot('is_active', true);
    }

    // Relationship with assistants
    public function assistants()
    {
        return $this->belongsToMany(Huanluyenvien::class, 'tro_giang_chi_nhanh', 'branch_id', 'assistant_id')
                    ->withPivot('is_active', 'assigned_at')
                    ->wherePivot('is_active', true);
    }

    // Scope for active branches
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

