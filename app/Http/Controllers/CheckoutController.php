<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Ticket;
use App\Models\User;

class CheckoutController extends Controller
{
    
    public function checkout(CheckoutRequest $request) {

        try {

            $user = User::latest()->first();

            $order = $user->orders()->create([
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($request->validated('tickets') as $item) {

                $ticket = Ticket::findOrFail($item['ticket_id']);

                $ticket->decrement('available_quantity', $item['quantity']);
                $lineTotal = $ticket->price * $item['quantity'];
                
                $order->items()->create([
                    'ticket_id' => $ticket->id,
                    'quantity' => $item['quantity'],
                    'price' => $ticket->price,
                ]);

                $total += $lineTotal;
            }

            $order->update(['total' => $total]);
            
            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Response processed',
            ], 200);
        
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
