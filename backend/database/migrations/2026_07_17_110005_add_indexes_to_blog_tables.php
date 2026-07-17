<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_categories', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('blog_tags', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('blogs', function (Blueprint $table): void {
            $table->unique('uuid');
            $table->index('status');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table): void {
            $table->dropIndex(['published_at']);
            $table->dropIndex(['status']);
            $table->dropUnique(['uuid']);
        });

        Schema::table('blog_tags', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });

        Schema::table('blog_categories', function (Blueprint $table): void {
            $table->dropUnique(['uuid']);
        });
    }
};
