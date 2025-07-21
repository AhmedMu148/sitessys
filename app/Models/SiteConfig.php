<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    use HasFactory;
    
    protected $table = 'site_config';
    
    protected $fillable = [
        'site_id',
        'settings',
        'data',
        'language_code',
        'tpl_name',
        'tpl_colors'
    ];
    
    protected $casts = [
        'settings' => 'array',
        'data' => 'array',
        'language_code' => 'array',
        'tpl_colors' => 'array'
    ];
    
    /**
     * Get the site this config belongs to
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get all settings as array
     */
    public function getSettingsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Get all data as array
     */
    public function getDataAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Get supported languages
     */
    public function getSupportedLanguages()
    {
        $languageCode = $this->language_code ?? [];
        return $languageCode['languages'] ?? ['en'];
    }

    /**
     * Get primary language
     */
    public function getPrimaryLanguage()
    {
        $languageCode = $this->language_code ?? [];
        return $languageCode['primary'] ?? 'en';
    }
}
