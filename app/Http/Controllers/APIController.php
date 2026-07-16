<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\KkpTokenService;
class APIController extends Controller
{
    public function cariPekerja(Request $request)
    {
        try {
            $token = KkpTokenService::getToken();
            $payload = json_encode([
                'paynumber' => trim((string) $request->paynumber),
            ]);

            $response = Http::withHeaders([
                'x-access-token' => $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->withBody($payload, 'application/json')
            ->get(config('services.kkp.url_pekerja'));

            return response()->json([
                'http_status' => $response->status(),
                'body' => $response->json(),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
