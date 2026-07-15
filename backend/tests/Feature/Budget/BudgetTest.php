<?php

declare(strict_types=1);

use App\Modules\Authentication\Models\User;
use App\Modules\Budget\Models\Budget;
use App\Modules\Budget\Models\BudgetCategory;
use App\Modules\Budget\Models\BudgetTransaction;
use App\Modules\Wedding\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $this->token = $this->user->createToken('auth-token')->plainTextToken;
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
});

test('authenticated user can create budget', function () {
    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/budgets', [
            'wedding_id' => $this->wedding->id,
            'title' => 'Wedding Budget',
            'description' => 'Main wedding budget',
            'total_budget' => 50000000,
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'wedding_id',
                'title',
                'description',
                'total_budget',
                'created_at',
                'updated_at',
            ],
        ]);

    $this->assertDatabaseHas('budgets', [
        'title' => 'Wedding Budget',
        'total_budget' => 50000000,
    ]);
});

test('authenticated user can list budgets', function () {
    Budget::factory()->count(3)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/budgets');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'total_budget',
                ],
            ],
        ]);
});

test('authenticated user can show budget with categories and transactions', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);
    BudgetTransaction::factory()->count(2)->create([
        'category_id' => $category->id,
        'type' => 'expense',
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/budgets/{$budget->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'title',
                'total_budget',
                'categories' => [
                    '*' => [
                        'id',
                        'name',
                        'allocated_amount',
                        'transactions',
                    ],
                ],
            ],
        ]);
});

test('authenticated user can update budget', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/budgets/{$budget->id}", [
            'title' => 'Updated Budget',
            'total_budget' => 75000000,
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.title', 'Updated Budget');

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'title' => 'Updated Budget',
        'total_budget' => 75000000,
    ]);
});

test('authenticated user can delete budget', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/budgets/{$budget->id}");

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted('budgets', ['id' => $budget->id]);
});

test('authenticated user can get budget summary', function () {
    $budget = Budget::factory()->create([
        'wedding_id' => $this->wedding->id,
        'total_budget' => 10000000,
    ]);
    $category = BudgetCategory::factory()->create([
        'budget_id' => $budget->id,
        'allocated_amount' => 5000000,
    ]);
    BudgetTransaction::factory()->create([
        'category_id' => $category->id,
        'type' => 'expense',
        'amount' => 1000000,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/budgets/{$budget->id}/summary");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_budget',
                'total_spent',
                'total_remaining',
                'percentage_used',
                'category_breakdown',
            ],
        ]);
});

test('authenticated user can duplicate budget', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);
    BudgetTransaction::factory()->count(2)->create(['category_id' => $category->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/budgets/{$budget->id}/duplicate");

    $response->assertStatus(200)
        ->assertJsonPath('data.title', $budget->title . ' (Copy)');

    $this->assertDatabaseHas('budgets', [
        'title' => $budget->title . ' (Copy)',
    ]);
});

test('authenticated user can create category', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/budgets/{$budget->id}/categories", [
            'name' => 'Venue',
            'allocated_amount' => 15000000,
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'allocated_amount',
            ],
        ]);

    $this->assertDatabaseHas('budget_categories', [
        'budget_id' => $budget->id,
        'name' => 'Venue',
    ]);
});

test('authenticated user can update category', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson("/api/budgets/{$budget->id}/categories/{$category->id}", [
            'name' => 'Updated Venue',
            'allocated_amount' => 20000000,
        ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Updated Venue');

    $this->assertDatabaseHas('budget_categories', [
        'id' => $category->id,
        'name' => 'Updated Venue',
    ]);
});

test('authenticated user can delete category', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/budgets/{$budget->id}/categories/{$category->id}");

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('budget_categories', ['id' => $category->id]);
});

test('authenticated user can create transaction', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/budgets/{$budget->id}/transactions", [
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 500000,
            'transaction_date' => '2026-07-16',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'type',
                'amount',
                'transaction_date',
            ],
        ]);

    $this->assertDatabaseHas('budget_transactions', [
        'category_id' => $category->id,
        'amount' => 500000,
    ]);
});

test('authenticated user can list transactions', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);
    BudgetTransaction::factory()->count(3)->create(['category_id' => $category->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/budgets/{$budget->id}/transactions");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'amount',
                    'transaction_date',
                ],
            ],
        ]);
});

test('authenticated user can update transaction', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);
    $transaction = BudgetTransaction::factory()->create([
        'category_id' => $category->id,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson(
            "/api/budgets/{$budget->id}/transactions/{$transaction->id}",
            ['amount' => 750000],
        );

    $response->assertStatus(200)
        ->assertJsonPath('data.amount', 750000);
});

test('authenticated user can delete transaction', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category = BudgetCategory::factory()->create(['budget_id' => $budget->id]);
    $transaction = BudgetTransaction::factory()->create(['category_id' => $category->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->deleteJson("/api/budgets/{$budget->id}/transactions/{$transaction->id}");

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('budget_transactions', ['id' => $transaction->id]);
});

test('authenticated user can get budget overview', function () {
    $budget1 = Budget::factory()->create([
        'wedding_id' => $this->wedding->id,
        'total_budget' => 50000000,
    ]);
    Budget::factory()->create([
        'wedding_id' => $this->wedding->id,
        'total_budget' => 30000000,
    ]);

    $category1 = BudgetCategory::factory()->create(['budget_id' => $budget1->id]);
    BudgetTransaction::factory()->create([
        'category_id' => $category1->id,
        'type' => 'expense',
        'amount' => 5000000,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/budgets/overview?wedding_id={$this->wedding->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_budget',
                'total_spent',
                'total_remaining',
                'percentage_used',
                'budget_count',
            ],
        ]);
});

test('authenticated user can reorder categories', function () {
    $budget = Budget::factory()->create(['wedding_id' => $this->wedding->id]);
    $category1 = BudgetCategory::factory()->create([
        'budget_id' => $budget->id,
        'sort_order' => 0,
    ]);
    $category2 = BudgetCategory::factory()->create([
        'budget_id' => $budget->id,
        'sort_order' => 1,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson(
            "/api/budgets/{$budget->id}/categories/reorder",
            ['order' => [$category2->id, $category1->id]],
        );

    $response->assertStatus(200);

    $this->assertDatabaseHas('budget_categories', [
        'id' => $category2->id,
        'sort_order' => 0,
    ]);
    $this->assertDatabaseHas('budget_categories', [
        'id' => $category1->id,
        'sort_order' => 1,
    ]);
});

test('authenticated user can recalculate budget', function () {
    $budget = Budget::factory()->create([
        'wedding_id' => $this->wedding->id,
        'total_budget' => 10000000,
    ]);
    $category = BudgetCategory::factory()->create([
        'budget_id' => $budget->id,
        'allocated_amount' => 5000000,
    ]);
    BudgetTransaction::factory()->create([
        'category_id' => $category->id,
        'type' => 'expense',
        'amount' => 2000000,
    ]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson("/api/budgets/{$budget->id}/recalculate");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_budget',
                'total_spent',
                'total_remaining',
                'percentage_used',
                'category_breakdown',
            ],
        ]);
});

test('unauthenticated user cannot access budgets', function () {
    $response = $this->getJson('/api/budgets');

    $response->assertStatus(401);
});

test('authenticated user cannot access other user budget', function () {
    $otherUser = User::factory()->create(['status' => User::STATUS_ACTIVE]);
    $otherWedding = Wedding::factory()->create(['user_id' => $otherUser->id]);
    $budget = Budget::factory()->create(['wedding_id' => $otherWedding->id]);

    $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson("/api/budgets/{$budget->id}");

    $response->assertStatus(404);
});
