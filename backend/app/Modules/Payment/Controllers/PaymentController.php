<?php

declare(strict_types=1);

namespace App\Modules\Payment\Controllers;

use App\Core\Base\Controller;
use App\Modules\Payment\Actions\CreatePaymentAction;
use App\Modules\Payment\Actions\GetPaymentAction;
use App\Modules\Payment\Actions\GetPaymentInvoiceAction;
use App\Modules\Payment\Actions\HandlePaymentCallbackAction;
use App\Modules\Payment\Actions\ListPaymentsAction;
use App\Modules\Payment\Actions\ProcessPaymentAction;
use App\Modules\Payment\Actions\RefundPaymentAction;
use App\Modules\Payment\Requests\ProcessPaymentRequest;
use App\Modules\Payment\Requests\RefundPaymentRequest;
use App\Modules\Payment\Requests\StorePaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentController extends Controller
{
    public function __construct(
        private readonly ListPaymentsAction $listPaymentsAction,
        private readonly GetPaymentAction $getPaymentAction,
        private readonly CreatePaymentAction $createPaymentAction,
        private readonly ProcessPaymentAction $processPaymentAction,
        private readonly HandlePaymentCallbackAction $handlePaymentCallbackAction,
        private readonly RefundPaymentAction $refundPaymentAction,
        private readonly GetPaymentInvoiceAction $getPaymentInvoiceAction,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->listPaymentsAction->execute(
            $request->user(),
            $request->only(['per_page', 'status'])
        );
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->createPaymentAction->execute(
                $request->user(),
                $request->validated()
            ),
        ], 201);
    }

    public function show(Request $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getPaymentAction->execute($request->user(), $uuid),
        ]);
    }

    public function pay(ProcessPaymentRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->processPaymentAction->execute(
                $request->user(),
                $uuid,
                $request->validated()
            ),
        ]);
    }

    public function callback(Request $request, string $gateway): JsonResponse
    {
        return $this->handlePaymentCallbackAction->execute($request, $gateway);
    }

    public function refund(RefundPaymentRequest $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->refundPaymentAction->execute(
                $request->user(),
                $uuid,
                $request->validated()
            ),
        ]);
    }

    public function invoice(Request $request, string $uuid): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->getPaymentInvoiceAction->execute($request->user(), $uuid),
        ]);
    }
}
