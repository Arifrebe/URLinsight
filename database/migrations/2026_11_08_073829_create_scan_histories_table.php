
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_histories', function (Blueprint $table) {
            $table->id();

            // Identitas unik setiap pemindaian
            $table->uuid('uuid')->unique();

            // NULL jika pemindaian dilakukan oleh guest
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // URL yang dianalisis
            $table->text('url');
            $table->string('domain')->nullable();

            // Hasil klasifikasi ML
            $table->string('prediction')->nullable();
            $table->decimal('confidence', 8, 6)->nullable();

            // Detail hasil analisis ML
            $table->json('indicators')->nullable();
            $table->json('explanation')->nullable();

            // Informasi model dan proses analisis
            $table->string('model_version')->nullable();
            $table->unsignedInteger('processing_time_ms')->nullable();

            // Status pemindaian
            $table->string('status')->default('pending');
            $table->string('error_code')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Index untuk kebutuhan pencarian dan filter
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_histories');
    }
};
