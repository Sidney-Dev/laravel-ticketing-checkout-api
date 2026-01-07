<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'event_id',
        'price',
        'title',
        'available_quantity',
    ];

    /**
     * Get the event that the ticket belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the order items for the ticket.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
