<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplLayoutType extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean'
    ];
    
    public function layouts()
    {
        return $this->hasMany(TplLayout::class, 'type_id');
    }
    
    public function designs()
    {
        return $this->hasMany(TplDesign::class, 'layout_type_id');
    }
}
