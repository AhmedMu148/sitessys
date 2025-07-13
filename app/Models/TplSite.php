<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplSite extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_site';
    
    protected $fillable = [
        'site_id',
        'nav',
        'pages',
        'footer'
    ];
    
    protected $casts = [
        'pages' => 'array'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    
    public function navLayout()
    {
        return $this->belongsTo(TplLayout::class, 'nav');
    }
    
    public function footerLayout()
    {
        return $this->belongsTo(TplLayout::class, 'footer');
    }
}
