<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user
        $testUser = User::where('email', 'test@example.com')->first();

        // Create 3 events for the test user
        Event::factory(3)->create([
            'user_id' => $testUser->id,
        ]);

        // Create 10 random events with random users
        Event::factory(10)->create();
    }
}
