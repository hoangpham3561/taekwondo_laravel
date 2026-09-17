<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCart extends Model
{
    protected $table = 'goi_hoc_phi';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_months',
        'classes_per_week',
        'club_id',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public function getProductIDAttribute()
    {
        return $this->id;
    }

    public function getProductNameAttribute()
    {
        return $this->name;
    }

    public function getPriceAttribute($value)
    {
        return (float) $value;
    }

    public function getAmountAttribute()
    {
        return null;
    }

    public function getActiveAttribute()
    {
        return $this->is_active ? 'Y' : 'N';
    }

    public function getAliasAttribute()
    {
        return null;
    }

    public function getPointAttribute()
    {
        return 0;
    }

    public function getProductTypeAttribute()
    {
        return json_encode(['2']);
    }

    public function getProductImageAttribute()
    {
        return null;
    }
}
