<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSocial extends Model
{
    use HasFactory;
    
    protected $table = 'site_social';
    
    protected $fillable = [
        'site_id',
        'data'
    ];
    
    protected $casts = [
        'data' => 'array'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
