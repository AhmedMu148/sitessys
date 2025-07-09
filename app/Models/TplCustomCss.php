<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplCustomCss extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_custom_css';
    
    protected $fillable = [
        'site_id',
        'content'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
