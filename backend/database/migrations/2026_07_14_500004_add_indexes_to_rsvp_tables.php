<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rsvps', function (Blueprint $table): void {
            $table->unique('guest_id');
        });

        Schema::table('rsvp_logs', function (Blueprint $table): void {
            $table->index('rsvp_id');
        });
    }

    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table): void {
            $table->dropUnique(['guest_id']);
        });

        Schema::table('rsvp_logs', function (Blueprint $table): void {
            $table->dropIndex(['rsvp_id']);
        });
    }
};
