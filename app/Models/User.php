<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status_id',
        'preferred_language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status_id' => 'boolean',
    ];

    /**
     * Accessor for is_active (alias for status_id)
     */
    public function getIsActiveAttribute()
    {
        return $this->status_id;
    }

    /**
     * Mutator for is_active (alias for status_id)
     */
    public function setIsActiveAttribute($value)
    {
        $this->status_id = $value;
    }

    /**
     * Get sites owned by this user
     */
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super-admin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super-admin']);
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status_id;
    }

    /**
     * Get the user's display name
     */
    public function getDisplayName()
    {
        return $this->name ?: $this->email;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }
        
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        
        return false;
    }

    /**
     * Check if user has specific role(s)
     */
    public function hasRole($roles)
    {
        return $this->hasAnyRole($roles);
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['updated_at' => now()]);
    }
}
