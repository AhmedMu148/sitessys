<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiAccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'token_name',
        'ip_address',
        'user_agent',
        'endpoint',
        'method',
        'request_data',
        'response_status',
        'accessed_at',
    ];

    protected $casts = [
        'request_data' => 'json',
        'accessed_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Get the user that made the request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log API access
     */
    public static function logAccess($request, $response, $user = null)
    {
        return static::create([
            'user_id' => $user?->id,
            'token_name' => $user?->currentAccessToken()?->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'request_data' => $request->except(['password', 'password_confirmation']),
            'response_status' => $response->getStatusCode(),
            'accessed_at' => now(),
        ]);
    }
}
