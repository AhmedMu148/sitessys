<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplPageSection extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'page_id',
        'tpl_layouts_id',
        'site_id',
        'name',
        'content',
        'content_data',
        'settings',
        'custom_styles',
        'custom_scripts',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'content' => 'array',
        'content_data' => 'array',
        'settings' => 'array',
        'status' => 'boolean'
    ];

    /**
     * Get the page this section belongs to
     */
    public function page()
    {
        return $this->belongsTo(TplPage::class, 'page_id');
    }

    /**
     * Get the site this section belongs to
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the layout template this section uses
     */
    public function layout()
    {
        return $this->belongsTo(TplLayout::class, 'tpl_layouts_id');
    }
    
    /**
     * Scope for active sections
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
     * Get content for specific locale
     */
    public function getContent($locale = 'en')
    {
        $content = $this->content ?? [];
        return $content[$locale] ?? [];
    }
}
