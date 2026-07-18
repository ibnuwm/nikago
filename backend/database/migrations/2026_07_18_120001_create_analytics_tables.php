<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table): void {
            $table->id();
            $table->string('event_type');
            $table->nullableMorphs('eventable');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('event_type');
            $table->index('tenant_id');
            $table->index('created_at');
        });

        Schema::create('analytics_reports', function (Blueprint $table): void {
            $table->id();
            $table->string('type');
            $table->json('filters')->nullable();
            $table->string('format')->default('csv');
            $table->string('file_path')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->index('type');
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_reports');
        Schema::dropIfExists('analytics_events');
    }
};
