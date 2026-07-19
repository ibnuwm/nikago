<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('webhook_id')->constrained()->cascadeOnDelete();
            $table->string('event', 100)->nullable();
            $table->text('payload')->nullable();
            $table->text('response')->nullable();
            $table->integer('status_code')->nullable();
            $table->string('status', 50)->default('pending');
            $table->integer('attempt')->default(1);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('webhook_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
