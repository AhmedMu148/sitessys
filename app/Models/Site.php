<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasConfiguration;

class Site extends Model
{
    use HasFactory, HasConfiguration;
    
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
     * Get the template site configuration (for navigation and social media)
     */
    public function tplSite()
    {
        return $this->hasOne(TplSite::class, 'site_id');
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

    /**
     * Get domain information from site configuration
     */
    public function getDomainData()
    {
        $config = $this->config;
        if (!$config || !$config->data) {
            return ['domains' => [], 'subdomains' => []];
        }

        $data = is_string($config->data) ? json_decode($config->data, true) : $config->data;
        return [
            'domains' => $data['domains'] ?? [],
            'subdomains' => $data['subdomains'] ?? []
        ];
    }

    /**
     * Set domain information in site configuration
     */
    public function setDomainData($domains = [], $subdomains = [])
    {
        $config = $this->config;
        if (!$config) {
            $config = new SiteConfig();
            $config->site_id = $this->id;
        }

        $data = is_string($config->data) ? json_decode($config->data, true) : ($config->data ?? []);
        $data['domains'] = $domains;
        $data['subdomains'] = $subdomains;
        
        $config->data = $data;
        $config->save();

        return $this;
    }

    /**
     * Check if a domain belongs to this site
     */
    public function hasDomain($domain)
    {
        $domainData = $this->getDomainData();
        return in_array($domain, $domainData['domains']);
    }

    /**
     * Check if a subdomain belongs to this site
     */
    public function hasSubdomain($subdomain)
    {
        $domainData = $this->getDomainData();
        return in_array($subdomain, $domainData['subdomains']);
    }

    /**
     * Get site by domain (static method)
     */
    public static function findByDomain($domain)
    {
        return self::whereHas('config', function ($query) use ($domain) {
            $query->whereRaw("JSON_SEARCH(data, 'one', ?) IS NOT NULL", [$domain])
                  ->orWhereRaw("JSON_SEARCH(JSON_EXTRACT(data, '$.domains'), 'one', ?) IS NOT NULL", [$domain]);
        })->first();
    }

    /**
     * Get site by subdomain (static method)
     */
    public static function findBySubdomain($subdomain)
    {
        return self::whereHas('config', function ($query) use ($subdomain) {
            $query->whereRaw("JSON_SEARCH(JSON_EXTRACT(data, '$.subdomains'), 'one', ?) IS NOT NULL", [$subdomain]);
        })->first();
    }
}
