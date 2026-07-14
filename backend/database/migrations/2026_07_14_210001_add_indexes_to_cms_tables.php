<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_pages', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('cms_banners', function (Blueprint $table): void {
            $table->unique('uuid');
        });

        Schema::table('cms_faqs', function (Blueprint $table): void {
            $table->unique('uuid');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table): void {
            $table->dropUnique('cms_pages_uuid_unique');
        });

        Schema::table('cms_banners', function (Blueprint $table): void {
            $table->dropUnique('cms_banners_uuid_unique');
        });

        Schema::table('cms_faqs', function (Blueprint $table): void {
            $table->dropUnique('cms_faqs_uuid_unique');
            $table->dropIndex('cms_faqs_category_index');
        });
    }
};
