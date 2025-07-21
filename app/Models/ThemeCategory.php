<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'sort_order'
    ];

    // Removed status field as it's not in the new schema

    public function themePages()
    {
        return $this->hasMany(ThemePage::class, 'category_id')->orderBy('sort_order');
    }
}
