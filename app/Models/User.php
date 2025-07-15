<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property bool $is_active
 * @property \Carbon\Carbon $last_login_at
 * @method \Illuminate\Database\Eloquent\Relations\HasMany templates()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany sites()
 * @method \Illuminate\Database\Eloquent\Relations\HasOne activeTemplate()
 * @method void updateLastLogin()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subdomain',
        'domain',
        'is_active',
        'phone',
        'bio',
        'avatar',
        'settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'settings' => 'json',
    ];

    /**
     * Get the sites for the user.
     */
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    /**
     * Check if user is owner (can see all sites)
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is admin (has one site)
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Get the user's primary site (for admins)
     */
    public function primarySite()
    {
        return $this->sites()->first();
    }

    /**
     * Get the user's templates
     */
    public function templates(): HasMany
    {
        return $this->hasMany(UserTemplate::class);
    }

    /**
     * Get the user's active template
     */
    public function activeTemplate(): HasOne
    {
        return $this->hasOne(UserTemplate::class)->where('is_active', true);
    }

    /**
     * Get the user's API access logs
     */
    public function apiAccessLogs()
    {
        return $this->hasMany(ApiAccessLog::class);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermissionTo($permission)
    {
        return $this->can($permission);
    }

    /**
     * Get user's domain URL
     */
    public function getDomainUrlAttribute()
    {
        if ($this->domain) {
            return 'https://' . $this->domain;
        }
        
        if ($this->subdomain) {
            return 'https://' . $this->subdomain . '.' . config('app.main_domain', 'example.com');
        }
        
        return null;
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdmin()
    {
        return $this->hasAnyRole(['super-admin', 'admin']) && $this->is_active;
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
