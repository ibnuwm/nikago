<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitation_templates', function (Blueprint $table): void {
            $table->index('favorites_count');
        });
    }

    public function down(): void
    {
        Schema::table('invitation_templates', function (Blueprint $table): void {
            $table->dropIndex(['favorites_count']);
        });
    }
};
