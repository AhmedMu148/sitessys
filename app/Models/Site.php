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
        'status'
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
    
    public function sections()
    {
        return $this->hasMany(TplSection::class);
    }
    
    public function pages()
    {
        return $this->hasMany(TplPage::class, 'site_id');
    }
    
    public function tplSite()
    {
        return $this->hasOne(TplSite::class);
    }
}
