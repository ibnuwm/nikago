<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rsvp_logs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('rsvp_id');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->timestamps();

            $table->foreign('rsvp_id')->references('id')->on('rsvps')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rsvp_logs');
    }
};
