<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'site_name',
        'url',
        'status_id',
        'active_header_id',
        'active_footer_id'
    ];
    
    protected $casts = [
        'status_id' => 'boolean'
    ];

    /**
     * Get the user that owns this site
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the site configuration
     */
    public function config()
    {
        return $this->hasOne(SiteConfig::class);
    }

    /**
     * Get the active header layout
     */
    public function activeHeader()
    {
        return $this->belongsTo(TplLayout::class, 'active_header_id');
    }

    /**
     * Get the active footer layout
     */
    public function activeFooter()
    {
        return $this->belongsTo(TplLayout::class, 'active_footer_id');
    }

    /**
     * Get site pages
     */
    public function pages()
    {
        return $this->hasMany(TplPage::class, 'site_id');
    }

    /**
     * Get site media
     */
    public function media()
    {
        return $this->hasMany(SiteImgMedia::class);
    }

    /**
     * Check if site is active
     */
    public function isActive()
    {
        return $this->status_id;
    }

    /**
     * Get the display name (site_name or fallback)
     */
    public function getDisplayName()
    {
        return $this->site_name ?: 'Untitled Site';
    }
}
