<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TplDesign extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'site_id',
        'page_id',
        'layout_id',
        'layout_type_id',
        'lang_code',
        'sort_order',
        'data',
        'status'
    ];
    
    protected $casts = [
        'data' => 'array',
        'status' => 'boolean'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
    
    public function page()
    {
        return $this->belongsTo(TplPage::class, 'page_id');
    }
    
    public function layout()
    {
        return $this->belongsTo(TplLayout::class, 'layout_id');
    }
    
    public function layoutType()
    {
        return $this->belongsTo(TplLayoutType::class, 'layout_type_id');
    }
    
    public function tplLayout()
    {
        return $this->belongsTo(TplLayout::class, 'layout_id');
    }
}
