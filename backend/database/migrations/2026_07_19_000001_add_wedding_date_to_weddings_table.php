<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table): void {
            $table->date('wedding_date')->nullable()->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table): void {
            $table->dropColumn('wedding_date');
        });
    }
};
