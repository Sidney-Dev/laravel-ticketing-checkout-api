<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    
    public function checkout(CheckoutRequest $request)
    {
        // ... business logic here
        
        return response()->json([
            'success' => true,
            'data' => $request->validated(),
            'message' => 'Response processed',
        ], 200);
    }
}
