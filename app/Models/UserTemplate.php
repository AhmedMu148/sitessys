<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'html_content',
        'css_content',
        'js_content',
        'config',
        'is_active',
        'is_default',
        'preview_image',
    ];

    protected $casts = [
        'config' => 'json',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the template
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get default templates
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get compiled CSS content
     */
    public function getCompiledCssAttribute()
    {
        // You can add SCSS compilation logic here if needed
        return $this->css_content;
    }

    /**
     * Get full template content
     */
    public function getFullContentAttribute()
    {
        $html = $this->html_content;
        
        if ($this->css_content) {
            $html = str_replace('</head>', "<style>{$this->css_content}</style></head>", $html);
        }
        
        if ($this->js_content) {
            $html = str_replace('</body>', "<script>{$this->js_content}</script></body>", $html);
        }
        
        return $html;
    }

    /**
     * Clone template for a user
     */
    public function cloneForUser(User $user, $name = null)
    {
        return static::create([
            'user_id' => $user->id,
            'name' => $name ?? ($this->name . ' (Copy)'),
            'description' => $this->description,
            'html_content' => $this->html_content,
            'css_content' => $this->css_content,
            'js_content' => $this->js_content,
            'config' => $this->config,
            'is_active' => false,
            'is_default' => false,
        ]);
    }
}
