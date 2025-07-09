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
        'lang_id',
        'direction',
        'is_default'
    ];
    
    protected $casts = [
        'is_default' => 'boolean'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
    
    public function language()
    {
        return $this->belongsTo(TplLang::class, 'lang_id');
    }
}
