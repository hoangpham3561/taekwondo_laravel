<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tin_tuc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'images',
        'featured_image_url',
        'author_id',
        'is_published',
        'published_at',
        'status',
        'cate_id',
        'seo_title',
        'seo_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'cate_id');
    }

    /**
     * Get status attribute (map from is_published)
     */
    public function getStatusAttribute()
    {
        if (isset($this->attributes['status'])) {
            return $this->attributes['status'];
        }
        // Map is_published to status if status column doesn't exist
        return $this->is_published ? 'active' : 'inactive';
    }

    /**
     * Set status attribute (map to is_published)
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
        // Also update is_published if status is being set
        if (in_array($value, ['active', 'published'])) {
            $this->attributes['is_published'] = true;
        } elseif (in_array($value, ['inactive', 'unpublished'])) {
            $this->attributes['is_published'] = false;
        }
    }

    /**
     * Get images attribute - handle both string and array
     * Returns string for backward compatibility with views
     */
    public function getImagesAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Try to decode JSON first
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // If it's an array, return the first image or the array itself
            return !empty($decoded) ? (is_array($decoded[0] ?? null) ? ($decoded[0]['url'] ?? $decoded[0]) : $decoded[0]) : null;
        }
        
        // If not JSON, return as string (single image filename)
        return $value;
    }

    /**
     * Get images as array
     */
    public function getImagesArrayAttribute()
    {
        $value = $this->attributes['images'] ?? null;
        if (empty($value)) {
            return [];
        }
        
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        
        return [$value];
    }

    /**
     * Set images attribute - convert to JSON if array
     */
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        } else {
            $this->attributes['images'] = $value;
        }
    }
}

