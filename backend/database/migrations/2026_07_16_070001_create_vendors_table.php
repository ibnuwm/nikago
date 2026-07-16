<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('business_name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('status')->default('active');
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_review')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('uuid');
            $table->unique('slug');
            $table->index('tenant_id');
            $table->index('business_name');
            $table->index('city');
            $table->index('province');
            $table->index('status');
            $table->index('rating');
        });

        Schema::create('vendor_services', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('starting_price', 15, 2)->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('vendor_packages', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->json('inclusions')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('vendor_portfolios', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('vendor_galleries', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('vendor_calendars', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->date('booked_date');
            $table->string('status')->default('booked');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->index('booked_date');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('vendor_teams', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('name');
            $table->string('position');
            $table->string('photo_url')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('vendor_documents', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('type');
            $table->string('file_url');
            $table->string('notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->index('type');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('vendor_verifications', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('verified_by');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_verifications');
        Schema::dropIfExists('vendor_documents');
        Schema::dropIfExists('vendor_teams');
        Schema::dropIfExists('vendor_calendars');
        Schema::dropIfExists('vendor_galleries');
        Schema::dropIfExists('vendor_portfolios');
        Schema::dropIfExists('vendor_packages');
        Schema::dropIfExists('vendor_services');
        Schema::dropIfExists('vendors');
    }
};
