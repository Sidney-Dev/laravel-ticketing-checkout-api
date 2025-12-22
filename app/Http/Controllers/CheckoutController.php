<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\JsonResponse;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{

    public function checkout(CheckoutRequest $request, CheckoutService $checkoutService): JsonResponse
    {
        try {
            $order = $checkoutService->checkout($request->validated());

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => "Order placed successfully",
            ], 201);
            
        } catch (\Throwable $e) {
            Log::error('Checkout failed', [
                'exception' => $e,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed',
            ], 400);
        }
    }
}
