<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'page_id',
        'layout_id', 
        'site_id',
        'name',
        'is_active',
        'sort_order',
        'content_data',
        'settings'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'content_data' => 'array',
        'settings' => 'array'
    ];
    
    // Relationships
    public function page()
    {
        return $this->belongsTo(TplPage::class, 'page_id');
    }
    
    public function layout()
    {
        return $this->belongsTo(TplLayout::class);
    }
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
