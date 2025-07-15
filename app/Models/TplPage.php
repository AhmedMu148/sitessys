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
        'section_id',
        'slug',
        'description',
        'is_active',
        'show_in_nav',
        'sort_order',
        'meta_data'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'show_in_nav' => 'boolean',
        'meta_data' => 'array'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    
    public function sections()
    {
        return $this->hasMany(PageSection::class, 'page_id');
    }
    
    public function activeSections()
    {
        return $this->sections()->active()->ordered();
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeInNav($query)
    {
        return $query->where('show_in_nav', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
