<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemePage extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'theme_id',
        'name',
        'description',
        'preview_image',
        'path',
        'css_variables',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'css_variables' => 'array',
        'status' => 'boolean'
    ];

    /**
     * Get the category this theme page belongs to
     */
    public function category()
    {
        return $this->belongsTo(ThemeCategory::class, 'category_id');
    }

    /**
     * Scope for active theme pages
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for ordered theme pages
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
