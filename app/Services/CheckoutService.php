<?php

namespace app\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function checkout(array $orderData): Order
    {
        return DB::transaction(function () use ($orderData) {
            $user = User::latest()->first();

            $order = $user->orders()->create([
                'status' => 'pending',
                'total' => 0,
            ]);

            $total = 0;

            foreach ($orderData['tickets'] as $item) {
                $ticket = Ticket::lockForUpdate()->findOrFail($item['ticket_id']);

                if ($ticket->event_id !== (int) $orderData['event_id']) {
                    throw new \RuntimeException('Ticket does not belong to the event.');
                }

                if ($ticket->available_quantity < $item['quantity']) {
                    throw new \RuntimeException('Insufficient ticket inventory.');
                }
                
                // update inventory
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

            return $order;
        });
    }
}