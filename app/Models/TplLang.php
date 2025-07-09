<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplLang extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_lang';
    
    protected $fillable = [
        'name',
        'code',
        'dir',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean'
    ];
    
    public function siteConfig()
    {
        return $this->hasMany(SiteConfig::class, 'lang_id');
    }
}
