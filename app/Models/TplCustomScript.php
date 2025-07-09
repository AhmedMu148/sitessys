<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplCustomScript extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_custom_scripts';
    
    protected $fillable = [
        'site_id',
        'location',
        'content'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
