<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteImgMedia extends Model
{
    use HasFactory;

    protected $table = 'site_img_media';

    protected $fillable = [
        'site_id',
        'section_id',
        'max_files',
        'allowed_types'
    ];

    protected $casts = [
        'allowed_types' => 'array'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function section()
    {
        return $this->belongsTo(TplPageSection::class, 'section_id');
    }

    public function isImageAllowed()
    {
        return in_array('image/*', $this->allowed_types ?? []);
    }

    public function hasReachedMaxFiles($currentCount)
    {
        return $currentCount >= $this->max_files;
    }
}
