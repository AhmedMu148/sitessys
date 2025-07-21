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
