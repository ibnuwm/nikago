<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table): void {
            $table->string('logo')->nullable()->after('slug');
            $table->string('cover')->nullable()->after('logo');
            $table->json('operating_hours')->nullable()->after('province');
            $table->json('social_media')->nullable()->after('operating_hours');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table): void {
            $table->dropColumn(['logo', 'cover', 'operating_hours', 'social_media']);
        });
    }
};
