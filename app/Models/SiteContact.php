<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteContact extends Model
{
    use HasFactory;
    
    protected $table = 'site_contact';
    
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
