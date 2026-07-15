<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('wedding_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('total_budget', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('wedding_id');
        });

        Schema::create('budget_categories', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('budget_id');
            $table->string('name');
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('budget_id');

            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
        });

        Schema::create('budget_transactions', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('category_id');
            $table->string('type')->default('expense');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();

            $table->index('category_id');
            $table->index('type');
            $table->index('transaction_date');

            $table->foreign('category_id')->references('id')->on('budget_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_transactions');
        Schema::dropIfExists('budget_categories');
        Schema::dropIfExists('budgets');
    }
};
