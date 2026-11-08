<?php

namespace Tests\Feature;

use App\Models\ScanHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ScanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_fails_when_ml_returns_invalid_indicator(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'classification' => [
                        'prediction' => 'phishing',
                        'confidence' => 0.95,
                    ],
                    'analysis' => [
                        'indicators' => [
                            [
                                'name' => 'url_length',
                                'value' => 85,
                                // description sengaja dihilangkan
                                'interpretation' => 'Relatif panjang',
                                'severity' => 'medium',
                            ],
                        ],
                        'explanation' => [
                            'method' => 'SHAP',
                            'summary' => 'Contoh analisis.',
                            'top_features' => [],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('scan.store'), [
                'url' => 'https://example.com',
            ]);

        $this->assertDatabaseHas('scan_histories', [
            'url' => 'https://example.com',
            'prediction' => null,
            'status' => 'failed',
            'error_code' => 'ML_SERVICE_ERROR',
        ]);
    }

    public function test_scan_fails_when_ml_returns_negative_processing_time(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'classification' => [
                        'prediction' => 'phishing',
                        'confidence' => 0.95,
                    ],
                    'analysis' => [
                        'indicators' => [],
                        'explanation' => [
                            'method' => 'SHAP',
                            'summary' => 'Contoh analisis.',
                            'top_features' => [],
                        ],
                    ],
                    'metadata' => [
                        'model_version' => '1.0.0',
                        'processing_time_ms' => -100,
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('scan.store'), [
                'url' => 'https://example.com',
            ]);

        $this->assertDatabaseHas('scan_histories', [
            'url' => 'https://example.com',
            'prediction' => null,
            'status' => 'failed',
            'error_code' => 'ML_SERVICE_ERROR',
        ]);
    }

    public function test_scan_fails_when_ml_returns_invalid_prediction(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'classification' => [
                        'prediction' => 'suspicious',
                        'confidence' => 0.95,
                    ],
                    'analysis' => [
                        'indicators' => [],
                        'explanation' => [],
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('scan.store'), [
                'url' => 'https://example.com',
            ]);

        $this->assertDatabaseHas('scan_histories', [
            'url' => 'https://example.com',
            'prediction' => null,
            'status' => 'failed',
            'error_code' => 'ML_SERVICE_ERROR',
        ]);
    }

    public function test_scan_fails_when_ml_returns_invalid_confidence(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'classification' => [
                        'prediction' => 'phishing',
                        'confidence' => 1.5,
                    ],
                    'analysis' => [
                        'indicators' => [],
                        'explanation' => [],
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('scan.store'), [
                'url' => 'https://example.com',
            ]);

        $this->assertDatabaseHas('scan_histories', [
            'url' => 'https://example.com',
            'prediction' => null,
            'status' => 'failed',
            'error_code' => 'ML_SERVICE_ERROR',
        ]);
    }

    public function test_modified_scan_result_link_cannot_be_accessed(): void
    {
        $scan = \App\Models\ScanHistory::create([
            'url' => 'https://example.com',
            'status' => 'completed',
            'prediction' => 'legitimate',
        ]);

        $signedUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'scan.result',
            now()->addHours(24),
            ['uuid' => $scan->uuid]
        );

        $modifiedUrl = $signedUrl . '&modified=1';

        $this->get($modifiedUrl)->assertForbidden();
    }

    public function test_expired_scan_result_link_cannot_be_accessed(): void
    {
        $scan = \App\Models\ScanHistory::create([
            'url' => 'https://example.com',
            'status' => 'completed',
            'prediction' => 'legitimate',
        ]);

        $expiredUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'scan.result',
            now()->subMinute(),
            ['uuid' => $scan->uuid]
        );

        $this->get($expiredUrl)->assertForbidden();
    }

    public function test_user_can_only_see_their_own_scan_history(): void
    {
        $userA = User::factory()->create([
            'is_active' => true,
        ]);

        $userB = User::factory()->create([
            'is_active' => true,
        ]);

        $scanA = \App\Models\ScanHistory::create([
            'user_id' => $userA->id,
            'url' => 'https://arif-example.com',
            'status' => 'completed',
            'prediction' => 'legitimate',
        ]);

        $scanB = \App\Models\ScanHistory::create([
            'user_id' => $userB->id,
            'url' => 'https://diva-example.com',
            'status' => 'completed',
            'prediction' => 'phishing',
        ]);

        $response = $this->actingAs($userA)
            ->get(route('scan.history'));

        $response->assertOk();

        $response->assertSee('arif-example.com');
        $response->assertDontSee('diva-example.com');
    }

    public function test_guest_cannot_access_scan_history(): void
    {
        $response = $this->get(route('scan.history'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_can_scan_url(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'classification' => [
                        'prediction' => 'legitimate',
                        'confidence' => 0.95,
                    ],
                    'analysis' => [
                        'indicators' => [],
                        'explanation' => [
                            'method' => 'SHAP',
                            'summary' => 'Tidak ditemukan karakteristik mencurigakan.',
                            'top_features' => [],
                        ],
                    ],
                    'metadata' => [
                        'model_version' => '1.0.0',
                        'processing_time_ms' => 120,
                    ],
                ],
            ], 200),
        ]);

        $response = $this->post(route('scan.store'), [
            'url' => 'https://example.com',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('scan_histories', [
            'user_id' => null,
            'url' => 'https://example.com',
            'prediction' => 'legitimate',
            'status' => 'completed',
        ]);

        $resultUrl = $response->headers->get('Location');

        $this->get($resultUrl)
            ->assertOk()
            ->assertSee('Legitimate');
    }

    public function test_invalid_url_is_rejected(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->from(route('scan.index'))
            ->post(route('scan.store'), [
                'url' => 'javascript:alert(1)',
            ]);

        $response->assertRedirect(route('scan.index'));
        $response->assertSessionHasErrors('url');

        $this->assertDatabaseCount('scan_histories', 0);
    }

    
    public function test_scan_is_marked_failed_when_ml_service_is_unavailable(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        Http::fake([
            '*' => Http::response([
                'success' => false,
                'error' => [
                    'code' => 'MODEL_UNAVAILABLE',
                    'message' => 'Model is currently unavailable.',
                ],
            ], 503),
        ]);

        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('scan.store'), [
            'url' => 'https://example.com',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('scan_histories', [
            'user_id' => $user->id,
            'url' => 'https://example.com',
            'prediction' => null,
            'status' => 'failed',
            'error_code' => 'ML_SERVICE_ERROR',
        ]);

        $resultUrl = $response->headers->get('Location');

        $this->get($resultUrl)
            ->assertOk()
            ->assertSee('Belum Dapat Menganalisis URL');
    }

    public function test_user_can_scan_url_and_receive_legitimate_result(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'classification' => [
                        'prediction' => 'legitimate',
                        'confidence' => 0.95,
                    ],
                    'analysis' => [
                        'indicators' => [],
                        'explanation' => [
                            'method' => 'SHAP',
                            'summary' => 'Tidak ditemukan karakteristik mencurigakan.',
                            'top_features' => [],
                        ],
                    ],
                    'metadata' => [
                        'model_version' => '1.0.0',
                        'processing_time_ms' => 120,
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('scan.store'), [
            'url' => 'https://example.com',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('scan_histories', [
            'user_id' => $user->id,
            'url' => 'https://example.com',
            'prediction' => 'legitimate',
            'status' => 'completed',
        ]);

        $resultUrl = $response->headers->get('Location');

        $this->get($resultUrl)
            ->assertOk()
            ->assertSee('Legitimate');
    }


    public function test_user_can_scan_url_and_receive_phishing_result(): void
    {
        config([
            'services.ml.url' => 'http://localhost:8001',
            'services.ml.token' => 'testing-token',
            'services.ml.timeout' => 15,
        ]);

        // Simulasi respons dari ML API.
        Http::fake([
            '*' => Http::response([
                'success' => true,
                'data' => [
                    'classification' => [
                        'prediction' => 'phishing',
                        'confidence' => 0.97,
                    ],
                    'analysis' => [
                        'indicators' => [
                            [
                                'name' => 'url_length',
                                'value' => 85,
                                'description' => 'Panjang URL',
                                'interpretation' => 'Relatif panjang',
                                'severity' => 'medium',
                            ],
                        ],
                        'explanation' => [
                            'method' => 'SHAP',
                            'summary' => 'Struktur URL memiliki karakteristik mencurigakan.',
                            'top_features' => [],
                        ],
                    ],
                    'metadata' => [
                        'model_version' => '1.0.0',
                        'processing_time_ms' => 150,
                    ],
                ],
            ], 200),
        ]);

        // Buat pengguna dan login.
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $this->actingAs($user);

        // Kirim URL untuk dianalisis.
        $response = $this->post(route('scan.store'), [
            'url' => 'https://example.com/login',
        ]);

        // Pastikan Laravel mengarahkan pengguna ke halaman hasil.
        $response->assertRedirect();

        // Pastikan hasil pemindaian tersimpan di database.
        $this->assertDatabaseHas('scan_histories', [
            'user_id' => $user->id,
            'url' => 'https://example.com/login',
            'prediction' => 'phishing',
            'status' => 'completed',
        ]);

        // Pastikan URL hasil memiliki signed URL yang valid.
        $resultUrl = $response->headers->get('Location');

        $this->get($resultUrl)
            ->assertOk()
            ->assertSee('Phishing');
    }
}
