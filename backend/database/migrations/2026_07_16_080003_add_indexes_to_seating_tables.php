<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seating_tables', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('seating_assignments', function (Blueprint $table): void {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('seating_tables', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('seating_assignments', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });
    }
};
