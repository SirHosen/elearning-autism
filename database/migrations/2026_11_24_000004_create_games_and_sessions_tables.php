<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('title', 100);
            $table->enum('game_type', ['pilih_benar', 'cocokkan', 'seret_tempatkan']);
            $table->tinyInteger('option_count')->default(3);
            $table->tinyInteger('round_count')->default(5);
            $table->string('instruction_text', 60);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->tinyInteger('rounds_total')->default(0);
            $table->tinyInteger('rounds_correct_first_try')->default(0);
            $table->integer('duration_seconds')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
        Schema::dropIfExists('games');
    }
};
