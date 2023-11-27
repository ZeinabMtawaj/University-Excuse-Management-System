<?php

// app/Http/Middleware/NormalizeTranslationKeys.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class NormalizeTranslationKeys
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Normalize translation keys if they exist in the request data
        if ($request->has('trans_keys')) {
            $normalizedKeys = array_map(function ($key) {
                return Str::lower($key);
            }, $request->get('trans_keys'));

            $request->merge(['trans_keys' => $normalizedKeys]);
        }

        return $next($request);
    }
}
