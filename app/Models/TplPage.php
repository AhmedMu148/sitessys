<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TplPage extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'site_id',
        'name',
        'slug',
        'sort_order',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean'
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
    
    public function designs()
    {
        return $this->hasMany(TplDesign::class, 'page_id');
    }
}
