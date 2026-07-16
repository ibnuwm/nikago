<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seating_tables', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('wedding_id');
            $table->foreign('wedding_id')->references('id')->on('weddings')->onDelete('cascade');
            $table->string('name');
            $table->integer('capacity')->default(8);
            $table->string('shape')->default('round');
            $table->integer('position_x')->nullable();
            $table->integer('position_y')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('wedding_id');
        });

        Schema::create('seating_assignments', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('guest_id');
            $table->integer('seat_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('table_id');
            $table->index('guest_id');
            $table->index('tenant_id');
            $table->unique(['table_id', 'guest_id']);

            $table->foreign('table_id')->references('id')->on('seating_tables')->onDelete('cascade');
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seating_assignments');
        Schema::dropIfExists('seating_tables');
    }
};
