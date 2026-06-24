<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PasienHistoriService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('PASIEN_HISTORI_API_URL', 'http://127.0.0.1:8000');
        $this->token   = env('PASIEN_HISTORI_API_TOKEN');
    }

    /**
     * Send medicine pickup data to pasien-histori application.
     */
    public function sendObatPickup(string $noRm, string $detailObat, ?string $tanggalAmbil = null): bool
    {
        if (empty($this->token)) {
            Log::warning('PasienHistoriService: API Token is empty, skipping integration call.');
            return false;
        }

        try {
            $response = Http::withToken($this->token)
                ->withoutVerifying()
                ->timeout(10)
                ->post($this->baseUrl . '/api/pasien-histori/obat', [
                    'no_rm'         => $noRm,
                    'detail_obat'   => $detailObat,
                    'tanggal_ambil' => $tanggalAmbil ?? date('Y-m-d H:i:s'),
                ]);

            if ($response->successful()) {
                Log::info('PasienHistoriService: Successfully synced obat pickup to pasien-histori.', [
                    'no_rm'    => $noRm,
                    'response' => $response->json()
                ]);
                return true;
            }

            Log::warning('PasienHistoriService: Failed to sync obat pickup.', [
                'no_rm'    => $noRm,
                'status'   => $response->status(),
                'response' => $response->body()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('PasienHistoriService Error: ' . $e->getMessage());
            return false;
        }
    }
}
