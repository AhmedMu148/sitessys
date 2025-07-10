<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'domain',
        'description',
        'default_lang_id',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean'
    ];
    
    public function pages()
    {
        return $this->hasMany(TplPage::class, 'site_id');
    }
    
    public function designs()
    {
        return $this->hasMany(TplDesign::class, 'site_id');
    }
    
    public function config()
    {
        return $this->hasMany(SiteConfig::class, 'site_id');
    }
    
    public function customCss()
    {
        return $this->hasMany(TplCustomCss::class, 'site_id');
    }
    
    public function customScripts()
    {
        return $this->hasMany(TplCustomScript::class, 'site_id');
    }
    
    public function colorPalette()
    {
        return $this->hasMany(TplColorPalette::class, 'site_id');
    }
}
