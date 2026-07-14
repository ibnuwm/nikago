<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitation_templates', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('slug');
            $table->string('category')->default('general');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('preview_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_premium')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('favorites_count')->default(0);
            $table->timestamps();

            $table->unique('slug');
            $table->index('category');
            $table->index('is_active');
            $table->index('is_premium');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_templates');
    }
};
