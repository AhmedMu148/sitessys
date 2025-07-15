<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplLayout extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type_id',
        'user_id',
        'site_id', 
        'name',
        'description',
        'data',
        'status',
        'is_active',
        'sort_order'
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'is_active' => 'boolean'
    ];
    
    public function type()
    {
        return $this->belongsTo(TplLayoutType::class, 'type_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
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
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeForSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
    
    public function scopeHeaders($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('name', 'nav');
        });
    }
    
    public function scopeFooters($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('name', 'footer');
        });
    }
    
    public function scopeSections($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('name', 'section');
        });
    }
}
