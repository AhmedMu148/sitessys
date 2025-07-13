<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplSection extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_sections';
    
    protected $fillable = [
        'site_id',
        'name',
        'content',
        'position',
        'lang_id'
    ];
    
    protected $casts = [
        'position' => 'integer'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
