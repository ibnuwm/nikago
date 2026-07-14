<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rsvps', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('guest_id');
            $table->string('attendance');
            $table->smallInteger('total_guest')->default(1);
            $table->text('message')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('attendance');
            $table->foreign('guest_id')->references('id')->on('guests')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rsvps');
    }
};
