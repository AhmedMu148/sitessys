<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplSite extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_site';
    
    protected $fillable = [
        'site_id',
        'nav',
        'footer',
        'nav_data',
        'footer_data'
    ];
    
    protected $casts = [
        'nav_data' => 'array',
        'footer_data' => 'array'
    ];

    /**
     * Get the site this template belongs to
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the navigation layout
     */
    public function navLayout()
    {
        return $this->belongsTo(TplLayout::class, 'nav');
    }

    /**
     * Get the footer layout
     */
    public function footerLayout()
    {
        return $this->belongsTo(TplLayout::class, 'footer');
    }

    /**
     * Get navigation links
     */
    public function getNavLinks()
    {
        $navData = $this->nav_data ?? [];
        return $navData['links'] ?? [];
    }

    /**
     * Get footer links
     */
    public function getFooterLinks()
    {
        $footerData = $this->footer_data ?? [];
        return $footerData['links'] ?? [];
    }
}
