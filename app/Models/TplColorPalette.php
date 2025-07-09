<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplColorPalette extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_color_palette';
    
    protected $fillable = [
        'site_id',
        'name',
        'color_code',
        'is_primary',
        'status'
    ];
    
    protected $casts = [
        'is_primary' => 'boolean',
        'status' => 'boolean'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
