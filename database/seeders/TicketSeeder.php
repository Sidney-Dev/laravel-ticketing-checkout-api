<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user with known credentials
        $event = Event::first('id');
        Ticket::create([
            'title' => "Example ticket",
            'event_id' => $event->id,
            'price' => 100,
            'available_quantity' => 4
        ]);

    }
}
