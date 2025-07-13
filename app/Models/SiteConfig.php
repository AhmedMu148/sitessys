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
        'data',
        'lang_id'
    ];
    
    protected $casts = [
        'data' => 'array'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
