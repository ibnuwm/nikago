<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wedding_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('package_id');
            $table->date('booking_date');
            $table->date('event_date');
            $table->decimal('subtotal', 18, 2);
            $table->decimal('discount', 18, 2)->default(0);
            $table->decimal('total', 18, 2);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('wedding_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->index('event_date');
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('vendor_packages')->onDelete('cascade');
        });

        Schema::create('booking_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('name');
            $table->decimal('price', 18, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->index('booking_id');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });

        Schema::create('booking_histories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('status_from')->nullable();
            $table->string('status_to');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();

            $table->index('booking_id');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });

        Schema::create('booking_documents', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('type'); // contract, invoice, attachment
            $table->string('file_url');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('booking_id');
            $table->index('type');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_documents');
        Schema::dropIfExists('booking_histories');
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('bookings');
    }
};
