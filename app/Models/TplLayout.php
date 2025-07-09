<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TplLayout extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'type_id',
        'name',
        'preview_image',
        'html_template',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean'
    ];
    
    public function type()
    {
        return $this->belongsTo(TplLayoutType::class, 'type_id');
    }
    
    public function designs()
    {
        return $this->hasMany(TplDesign::class, 'layout_id');
    }
}
