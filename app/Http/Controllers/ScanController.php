<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScanUrlRequest;
use App\Models\ScanHistory;
use App\Services\MachineLearning\PredictionService;
use Illuminate\Support\Facades\URL;
use Throwable;
use RuntimeException;

class ScanController extends Controller
{
    /**
     * Menampilkan halaman pemindaian.
     */
    public function index()
    {
        return view('front.scan.index');
    }

    /**
     * Memproses URL dan menyimpan hasil pemindaian.
     */
    public function store(
        ScanUrlRequest $request,
        PredictionService $predictionService
    ) {
        $url = $request->validated()['url'];

        // Simpan pemindaian terlebih dahulu dengan status pending.
        $scan = ScanHistory::create([
            'user_id' => $request->user()?->id,
            'url' => $url,
            'domain' => parse_url($url, PHP_URL_HOST),
            'status' => 'pending',
        ]);

        try {
            // Kirim URL ke Machine Learning API.
            $result = $predictionService->predict($url);

            $prediction = data_get(
                $result,
                'classification.prediction'
            );

            $confidence = data_get(
                $result,
                'classification.confidence'
            );

            $indicators = data_get(
                $result,
                'analysis.indicators'
            );

            $explanation = data_get(
                $result,
                'analysis.explanation'
            );

            if (!is_array($indicators) || !is_array($explanation)) {
                throw new RuntimeException('Detail analisis dari ML API tidak valid.');
            }

            $processingTime = data_get($result, 'metadata.processing_time_ms');
            $modelVersion = data_get($result, 'metadata.model_version');

            if (
                $processingTime !== null &&
                (
                    filter_var($processingTime, FILTER_VALIDATE_INT) === false ||
                    (int) $processingTime < 0
                )
            ) {
                throw new RuntimeException(
                    'Processing time dari ML API tidak valid.'
                );
            }

            if (
                $modelVersion !== null &&
                (!is_string($modelVersion) || strlen($modelVersion) > 255)
            ) {
                throw new RuntimeException(
                    'Model version dari ML API tidak valid.'
                );
            }

            // Pastikan hasil klasifikasi sesuai kontrak API.
            if (!in_array($prediction, ['phishing', 'legitimate'], true)) {
                throw new RuntimeException(
                    'Hasil klasifikasi dari ML API tidak valid.'
                );
            }

            if (
                $confidence !== null &&
                (!is_numeric($confidence) ||
                $confidence < 0 ||
                $confidence > 1)
            ) {
                throw new RuntimeException(
                    'Nilai confidence dari ML API tidak valid.'
                );
            }

            if (!is_array($indicators) || !is_array($explanation)) {
                throw new RuntimeException('Detail analisis dari ML API tidak valid.');
            }

            foreach ($indicators as $indicator) {
                if (
                    !is_array($indicator) ||
                    !is_string($indicator['name'] ?? null) ||
                    !is_scalar($indicator['value'] ?? null) ||
                    !is_string($indicator['description'] ?? null) ||
                    !is_string($indicator['interpretation'] ?? null) ||
                    !is_string($indicator['severity'] ?? null)
                ) {
                    throw new RuntimeException('Struktur indikator ML tidak valid.');
                }
            }
            
            $topFeatures = $explanation['top_features'] ?? null;

            if (
                !is_string($explanation['method'] ?? null) ||
                !is_string($explanation['summary'] ?? null) ||
                !is_array($topFeatures)
            ) {
                throw new RuntimeException('Struktur explanation ML tidak valid.');
            }

            foreach ($topFeatures as $feature) {
                if (
                    !is_array($feature) ||
                    !is_string($feature['name'] ?? null) ||
                    !is_numeric($feature['value'] ?? null) ||
                    !is_numeric($feature['contribution'] ?? null) ||
                    !in_array(
                        $feature['direction'] ?? null,
                        ['phishing', 'legitimate'],
                        true
                    )
                ) {
                    throw new RuntimeException('Struktur top features ML tidak valid.');
                }
            }

            // Simpan hasil prediksi.
            $scan->update([
                'prediction' => $prediction,
                'confidence' => $confidence,
                'indicators' => $indicators,
                'explanation' => $explanation,
                'model_version' => $modelVersion,
                'processing_time_ms' => $processingTime,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

        } catch (Throwable $exception) {
            // Catat error untuk kebutuhan debugging.
            report($exception);

            // Jangan pernah menganggap kegagalan ML sebagai legitimate.
            $scan->update([
                'status' => 'failed',
                'error_code' => 'ML_SERVICE_ERROR',
                'completed_at' => now(),
            ]);
        }

        // Buat link hasil yang berlaku selama 24 jam.
        $resultUrl = URL::temporarySignedRoute(
            'scan.result',
            now()->addHours(24),
            ['uuid' => $scan->uuid]
        );

        return redirect($resultUrl);
    }

    /**
     * Menampilkan hasil pemindaian.
     */
    public function result(string $uuid)
    {
        $scan = ScanHistory::where('uuid', $uuid)->firstOrFail();

        return view('front.scan.result', compact('scan'));
    }
    
    /**
     * Menampilkan riwayat pemindaian pengguna yang login.
     */
    public function history()
    {
        $scans = auth()->user()
            ->scanHistories()
            ->latest()
            ->paginate(10);

        return view('front.scan.history', compact('scans'));
    }

}
