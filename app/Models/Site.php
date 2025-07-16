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
        'domain',
        'status',
        'active_header_id',
        'active_footer_id'
    ];
    
    protected $casts = [
        'status' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function config()
    {
        return $this->hasMany(SiteConfig::class);
    }
    
    public function social()
    {
        return $this->hasOne(SiteSocial::class);
    }
    
    public function contact()
    {
        return $this->hasOne(SiteContact::class);
    }
    
    public function seoIntegrations()
    {
        return $this->hasMany(SiteSeoInt::class);
    }
    
    public function pages()
    {
        return $this->hasMany(TplPage::class, 'site_id');
    }
    
    public function tplSite()
    {
        return $this->hasOne(TplSite::class);
    }
    
    // Active header/footer relationships
    public function activeHeader()
    {
        return $this->belongsTo(TplLayout::class, 'active_header_id');
    }
    
    public function activeFooter()
    {
        return $this->belongsTo(TplLayout::class, 'active_footer_id');
    }
    
    // All layouts owned by this site
    public function layouts()
    {
        return $this->hasMany(TplLayout::class);
    }
    
    // Header layouts for this site
    public function headerLayouts()
    {
        return $this->layouts()->whereHas('type', function ($query) {
            $query->where('name', 'nav');
        });
    }
    
    // Footer layouts for this site
    public function footerLayouts()
    {
        return $this->layouts()->whereHas('type', function ($query) {
            $query->where('name', 'footer');
        });
    }
    
    // Section layouts for this site
    public function sectionLayouts()
    {
        return $this->layouts()->whereHas('type', function ($query) {
            $query->where('name', 'section');
        });
    }
    
    // Page sections
    public function pageSections()
    {
        return $this->hasMany(PageSection::class);
    }
}
