<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TplLang extends Model
{
    use HasFactory;
    
    protected $table = 'tpl_langs';
    
    protected $fillable = [
        'code',
        'name',
        'dir'
    ];
    
    // Removed scopeActive as status field doesn't exist in new schema

    public function isRtl()
    {
        return $this->dir === 'rtl';
    }

    public function isLtr()
    {
        return $this->dir === 'ltr';
    }
}
