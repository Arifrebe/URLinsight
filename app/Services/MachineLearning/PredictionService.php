<?php

namespace App\Services\MachineLearning;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PredictionService
{
    public function predict(string $url): array
    {
        $baseUrl = config('services.ml.url');
        $token = config('services.ml.token');
        $timeout = config('services.ml.timeout', 15);

        if (empty($baseUrl) || empty($token)) {
            throw new RuntimeException(
                'Konfigurasi Machine Learning API belum lengkap.'
            );
        }

        $response = Http::acceptJson()
            ->withToken($token)
            ->timeout($timeout)
            ->post(
                rtrim($baseUrl, '/') . '/api/v1/predict',
                [
                    'url' => $url,
                ]
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Machine Learning API mengembalikan HTTP ' .
                $response->status()
            );
        }

        $payload = $response->json();

        if (
            !is_array($payload) ||
            ($payload['success'] ?? false) !== true ||
            !is_array($payload['data'] ?? null)
        ) {
            throw new RuntimeException(
                'Format respons Machine Learning API tidak valid.'
            );
        }

        return $payload['data'];
    }
}
