<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplLayout extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'tpl_id',
        'layout_type',
        'name',
        'description',
        'preview_image',
        'path',
        'default_config',
        'content',
        'configurable_fields',
        'status',
        'sort_order'
    ];
    
    protected $casts = [
        'default_config' => 'array',
        'content' => 'array',
        'configurable_fields' => 'array',
        'status' => 'boolean'
    ];

    /**
     * Get sites using this layout as header
     */
    public function sitesUsingAsHeader()
    {
        return $this->hasMany(Site::class, 'active_header_id');
    }

    /**
     * Get sites using this layout as footer
     */
    public function sitesUsingAsFooter()
    {
        return $this->hasMany(Site::class, 'active_footer_id');
    }
    
    /**
     * Scope to get header layouts
     */
    public function scopeHeaders($query)
    {
        return $query->where('layout_type', 'header');
    }
    
    /**
     * Scope to get footer layouts
     */
    public function scopeFooters($query)
    {
        return $query->where('layout_type', 'footer');
    }

    /**
     * Scope to get section layouts
     */
    public function scopeSections($query)
    {
        return $query->where('layout_type', 'section');
    }

    /**
     * Scope for active layouts
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Check if this is a header layout
     */
    public function isHeader()
    {
        return $this->layout_type === 'header';
    }

    /**
     * Check if this is a footer layout
     */
    public function isFooter()
    {
        return $this->layout_type === 'footer';
    }
}
