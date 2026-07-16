<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class KkpTokenService
{
    const CACHE_KEY = 'kkp_api_token';

    public static function getToken()
    {
        // 1. Jika token masih ada dalam cache → guna
        if (Cache::has(self::CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY);
        }

        // 2. Jika tiada → login
        return self::refreshToken();
    }

    public static function refreshToken()
    {
        // dd(config('services.kkp.login_url'));
        
        $payload = [
            'id'       => config('services.kkp.id'),
            'password' => config('services.kkp.password'),
            'entity'   => config('services.kkp.entity'),
            'token'    => config('services.kkp.static_token'),
        ];

        $response = Http::asJson()
            ->post(config('services.kkp.login_url'), $payload);
            // dd($response->json());

        if (! $response->successful()) {
            throw new \Exception('KKP authentication failed');
        }

        $token = $response->json('token');

        if (! $token) {
            throw new \Exception('Token not found in response');
        }

        // Simpan token (contoh: 6 hari – selamat dari expiry 7 hari)
        Cache::put(self::CACHE_KEY, $token, now()->addDays(6));

        return $token;
    }
}
