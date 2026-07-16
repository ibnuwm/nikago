<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedTinyInteger('rating');
            $table->text('review')->nullable();
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->string('status')->default('approved');
            $table->timestamps();

            $table->unique('uuid');
            $table->unique('booking_id');
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('review_images', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('review_id');
            $table->string('image_url');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('review_id');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
        });

        Schema::create('review_reports', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('review_id');
            $table->unsignedBigInteger('user_id');
            $table->text('reason');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique('uuid');
            $table->index('review_id');
            $table->index('user_id');
            $table->index('status');
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reports');
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('reviews');
    }
};
