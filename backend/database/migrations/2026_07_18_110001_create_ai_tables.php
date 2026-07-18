<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_prompts', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name', 255);
            $table->text('system_prompt');
            $table->text('user_prompt_template');
            $table->string('model', 100)->default('openai/gpt-4o-mini');
            $table->float('temperature', 8, 2)->default(0.7);
            $table->integer('max_tokens')->default(2048);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ai_usage', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('feature', 100);
            $table->string('model', 100);
            $table->integer('prompt_tokens')->default(0);
            $table->integer('completion_tokens')->default(0);
            $table->integer('total_tokens')->default(0);
            $table->decimal('cost', 12, 6)->default(0);
            $table->timestamps();

            $table->index('user_id');
            $table->index('feature');
            $table->index('created_at');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('ai_history', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('user_id');
            $table->string('feature', 100);
            $table->text('prompt');
            $table->longText('response')->nullable();
            $table->string('model', 100);
            $table->integer('prompt_tokens')->default(0);
            $table->integer('completion_tokens')->default(0);
            $table->timestamps();

            $table->unique('uuid');
            $table->index('user_id');
            $table->index('feature');
            $table->index('created_at');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_history');
        Schema::dropIfExists('ai_usage');
        Schema::dropIfExists('ai_prompts');
    }
};
