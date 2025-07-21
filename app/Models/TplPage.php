<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplPage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'site_id',
        'name',
        'link',
        'slug',
        'data',
        'show_in_nav',
        'status',
        'page_theme_id'
    ];
    
    protected $casts = [
        'data' => 'array',
        'show_in_nav' => 'boolean',
        'status' => 'boolean'
    ];

    /**
     * Get the site this page belongs to
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the theme page this page is based on
     */
    public function themePage()
    {
        return $this->belongsTo(ThemePage::class, 'page_theme_id');
    }

    /**
     * Get sections for this page
     */
    public function sections()
    {
        return $this->hasMany(TplPageSection::class, 'page_id');
    }

    /**
     * Scope for active pages
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for pages shown in navigation
     */
    public function scopeInNav($query)
    {
        return $query->where('show_in_nav', true);
    }

    /**
     * Get the page title from data or fallback to name
     */
    public function getTitle($locale = 'en')
    {
        $data = $this->data ?? [];
        return $data[$locale]['title'] ?? $this->name;
    }
}
