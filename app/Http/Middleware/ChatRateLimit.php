<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChatRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Rate limiting: 30 messages per minute per user
        $key = 'chat_rate_limit_' . $userId;
        $maxAttempts = 30;
        $decayMinutes = 1;
        
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= $maxAttempts) {
            return response()->json([
                'error' => 'Too many messages. Please wait before sending another message.',
                'retry_after' => 60
            ], 429);
        }
        
        // Increment attempts
        Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));
        
        return $next($request);
    }
}
