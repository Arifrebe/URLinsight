
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('forum_categories')
                ->restrictOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');

            $table->string('status', 20)->default('published');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['category_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
