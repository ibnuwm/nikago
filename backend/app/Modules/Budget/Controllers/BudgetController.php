<?php

declare(strict_types=1);

namespace App\Modules\Budget\Controllers;

use App\Core\Base\Controller;
use App\Modules\Budget\Actions\CreateBudgetAction;
use App\Modules\Budget\Actions\CreateCategoryAction;
use App\Modules\Budget\Actions\CreateTransactionAction;
use App\Modules\Budget\Actions\DeleteBudgetAction;
use App\Modules\Budget\Actions\DeleteCategoryAction;
use App\Modules\Budget\Actions\DeleteTransactionAction;
use App\Modules\Budget\Actions\DuplicateBudgetAction;
use App\Modules\Budget\Actions\GetBudgetAction;
use App\Modules\Budget\Actions\GetBudgetOverviewAction;
use App\Modules\Budget\Actions\GetBudgetsAction;
use App\Modules\Budget\Actions\GetBudgetSummaryAction;
use App\Modules\Budget\Actions\GetTransactionsAction;
use App\Modules\Budget\Actions\RecalculateBudgetAction;
use App\Modules\Budget\Actions\ReorderCategoriesAction;
use App\Modules\Budget\Actions\UpdateBudgetAction;
use App\Modules\Budget\Actions\UpdateCategoryAction;
use App\Modules\Budget\Actions\UpdateTransactionAction;
use App\Modules\Budget\Requests\StoreBudgetRequest;
use App\Modules\Budget\Requests\StoreCategoryRequest;
use App\Modules\Budget\Requests\StoreTransactionRequest;
use App\Modules\Budget\Requests\UpdateBudgetRequest;
use App\Modules\Budget\Requests\UpdateCategoryRequest;
use App\Modules\Budget\Requests\UpdateTransactionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BudgetController extends Controller
{
    public function __construct(
        private readonly GetBudgetsAction $getBudgetsAction,
        private readonly CreateBudgetAction $createBudgetAction,
        private readonly GetBudgetAction $getBudgetAction,
        private readonly UpdateBudgetAction $updateBudgetAction,
        private readonly DeleteBudgetAction $deleteBudgetAction,
        private readonly GetBudgetSummaryAction $getBudgetSummaryAction,
        private readonly CreateCategoryAction $createCategoryAction,
        private readonly UpdateCategoryAction $updateCategoryAction,
        private readonly DeleteCategoryAction $deleteCategoryAction,
        private readonly GetTransactionsAction $getTransactionsAction,
        private readonly CreateTransactionAction $createTransactionAction,
        private readonly UpdateTransactionAction $updateTransactionAction,
        private readonly DeleteTransactionAction $deleteTransactionAction,
        private readonly DuplicateBudgetAction $duplicateBudgetAction,
        private readonly GetBudgetOverviewAction $getBudgetOverviewAction,
        private readonly ReorderCategoriesAction $reorderCategoriesAction,
        private readonly RecalculateBudgetAction $recalculateBudgetAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->getBudgetsAction->execute(
            $request->user(),
            $request->only(['per_page', 'wedding_id'])
        );
    }

    public function store(StoreBudgetRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createBudgetAction->execute($request, $request->user()),
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getBudgetAction->execute($request->user(), $id),
        ]);
    }

    public function update(UpdateBudgetRequest $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateBudgetAction->execute($request, $request->user(), $id),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $deleted = $this->deleteBudgetAction->execute($request, $id);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Budget not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Budget deleted successfully.',
        ]);
    }

    public function summary(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getBudgetSummaryAction->execute($request->user(), $id),
        ]);
    }

    public function duplicate(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->duplicateBudgetAction->execute($request->user(), $id),
        ]);
    }

    public function overview(Request $request): JsonResponse
    {
        $weddingId = (int) $request->input('wedding_id');

        return response()->json([
            'success' => true,
            'data' => $this->getBudgetOverviewAction->execute($request->user(), $weddingId),
        ]);
    }

    public function storeCategory(StoreCategoryRequest $request, int $budgetId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createCategoryAction->execute($request, $request->user(), $budgetId),
        ], 201);
    }

    public function updateCategory(UpdateCategoryRequest $request, int $budgetId, int $categoryId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateCategoryAction->execute($request, $request->user(), $budgetId, $categoryId),
        ]);
    }

    public function destroyCategory(Request $request, int $budgetId, int $categoryId): JsonResponse
    {
        $deleted = $this->deleteCategoryAction->execute($request, $budgetId, $categoryId);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Budget or category not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }

    public function reorderCategories(Request $request, int $budgetId): JsonResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:budget_categories,id'],
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->reorderCategoriesAction->execute(
                $request->user(),
                $budgetId,
                $request->input('order')
            ),
        ]);
    }

    public function indexTransactions(Request $request, int $budgetId): AnonymousResourceCollection
    {
        return $this->getTransactionsAction->execute(
            $request->user(),
            $budgetId,
            $request->has('category_id') ? (int) $request->input('category_id') : null,
            $request->only(['per_page', 'type', 'from_date', 'to_date'])
        );
    }

    public function storeTransaction(StoreTransactionRequest $request, int $budgetId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createTransactionAction->execute($request, $request->user(), $budgetId),
        ], 201);
    }

    public function updateTransaction(UpdateTransactionRequest $request, int $budgetId, int $transactionId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->updateTransactionAction->execute($request, $request->user(), $budgetId, $transactionId),
        ]);
    }

    public function destroyTransaction(Request $request, int $budgetId, int $transactionId): JsonResponse
    {
        $deleted = $this->deleteTransactionAction->execute($request, $budgetId, $transactionId);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Budget or transaction not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully.',
        ]);
    }

    public function recalculate(Request $request, int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->recalculateBudgetAction->execute($request->user(), $id),
        ]);
    }
}
