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
            $table->boolean('featured')->default(false)->after('verified_at');
            $table->timestamp('featured_at')->nullable()->after('featured');
        });

        Schema::create('wishlists', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->timestamps();

            $table->unique('uuid');
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('vendor_id');
            $table->unique(['user_id', 'vendor_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        Schema::create('compare_lists', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->timestamps();

            $table->unique('uuid');
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('vendor_id');
            $table->unique(['user_id', 'vendor_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compare_lists');
        Schema::dropIfExists('wishlists');

        Schema::table('vendors', function (Blueprint $table): void {
            $table->dropColumn(['featured', 'featured_at']);
        });
    }
};
