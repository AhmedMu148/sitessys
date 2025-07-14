<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSeoInt extends Model
{
    use HasFactory;
    
    protected $table = 'site_seo_int';
    
    protected $fillable = [
        'site_id',
        'int_name',
        'data',
        'status'
    ];
    
    protected $casts = [
        'data' => 'array',
        'status' => 'boolean'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
