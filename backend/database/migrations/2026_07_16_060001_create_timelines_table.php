<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timelines', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('wedding_id');
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('progress', 5, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('wedding_id');
        });

        Schema::create('timeline_tasks', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('timeline_id');
            $table->softDeletes();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('priority')->default('medium');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('duration_days')->default(1);
            $table->timestamp('completed_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('timeline_id');
            $table->index('priority');
            $table->index('due_date');

            $table->foreign('timeline_id')->references('id')->on('timelines')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_tasks');
        Schema::dropIfExists('timelines');
    }
};