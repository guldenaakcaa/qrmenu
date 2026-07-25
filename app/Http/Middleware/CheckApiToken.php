<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Yetkisiz erişim. Token bulunamadı.'
            ], 401);
        }

        $user = DB::table('users')->where('api_token', $token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Yetkisiz erişim. Geçersiz token.'
            ], 401);
        }

        // İstek yapan kullanıcı bilgisini request'e ekleyebiliriz (opsiyonel)
        $request->merge(['api_user_id' => $user->id]);

        return $next($request);
    }
}
