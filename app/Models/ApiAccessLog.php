<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiAccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'method',
        'url',
        'status_code',
        'response_time',
        'user_agent'
    ];

    public static function logAccess(Request $request, Response $response, $user = null)
    {
        // For now, just create a simple log entry
        // In production, you might want to store this in a separate table
        Log::info('API Access', [
            'user_id' => $user?->id,
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'user_agent' => $request->userAgent()
        ]);
        
        // Return true to avoid breaking the middleware
        return true;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
