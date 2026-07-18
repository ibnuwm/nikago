<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->string('provider', 100)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->json('configuration')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id')->index();
            $table->unsignedBigInteger('user_id');
            $table->string('invoice_number', 100)->unique();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->decimal('amount', 18, 2);
            $table->string('status')->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });

        Schema::create('payment_items', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->string('item_type', 50);
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name');
            $table->decimal('amount', 18, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->index('payment_id');
            $table->index(['item_type', 'item_id']);
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });

        Schema::create('payment_transactions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->string('gateway', 50);
            $table->string('transaction_id', 255)->nullable();
            $table->string('type', 50); // charge, refund
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->index('payment_id');
            $table->index('transaction_id');
            $table->index('gateway');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });

        Schema::create('payment_callbacks', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('payment_id')->nullable()->index();
            $table->string('gateway', 50)->index();
            $table->json('headers')->nullable();
            $table->json('body')->nullable();
            $table->string('signature', 255)->nullable();
            $table->string('status');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
        });

        Schema::create('refunds', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('payment_id');
            $table->decimal('amount', 18, 2);
            $table->text('reason');
            $table->string('status')->default('pending');
            $table->string('gateway_transaction_id', 255)->nullable();
            $table->timestamps();

            $table->unique('uuid');
            $table->index('payment_id');
            $table->index('status');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payment_callbacks');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payment_items');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_methods');
    }
};
