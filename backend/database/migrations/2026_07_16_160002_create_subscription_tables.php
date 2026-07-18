<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 18, 2);
            $table->decimal('yearly_price', 18, 2)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('plan_id');
            $table->string('status')->default('active');
            $table->timestamp('started_at');
            $table->timestamp('expired_at');
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index('status');
            $table->unique(['tenant_id', 'status'], 'active_subscription_unique');
            $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
        });

        Schema::create('subscription_histories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('action', 50); // subscribed, upgraded, downgraded, cancelled, renewed
            $table->unsignedBigInteger('old_plan_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('subscription_id');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
        });

        Schema::create('subscription_features', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->string('code', 50);
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('plan_id');
            $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
        });

        Schema::create('feature_limits', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->string('feature_code', 50);
            $table->string('limit_value', 100);
            $table->timestamps();

            $table->index('plan_id');
            $table->foreign('plan_id')->references('id')->on('subscription_plans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_limits');
        Schema::dropIfExists('subscription_features');
        Schema::dropIfExists('subscription_histories');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
