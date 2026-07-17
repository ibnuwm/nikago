<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rundowns', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('wedding_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('wedding_id');
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('cascade');
        });

        Schema::create('rundown_items', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('rundown_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('pic')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('rundown_id');
            $table->index('sort_order');

            $table->foreign('rundown_id')->references('id')->on('rundowns')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rundown_items');
        Schema::dropIfExists('rundowns');
    }
};
