<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('source', 100)->nullable();
            $table->string('stage', 50)->default('new')->index();
            $table->decimal('deal_value', 18, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index('vendor_id');
            $table->index('user_id');
            $table->index('assigned_to');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('lead_follow_ups', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('lead_id');
            $table->string('type', 50);
            $table->text('notes');
            $table->timestamp('follow_up_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index('lead_id');
            $table->index('is_completed');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });

        Schema::create('lead_activities', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('lead_id');
            $table->string('type', 50);
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index('lead_id');
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('lead_follow_ups');
        Schema::dropIfExists('leads');
    }
};
