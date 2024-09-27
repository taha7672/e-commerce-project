<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketPriority;

class TicketPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priorities = [
            ['id' => 1, 'name' => 'Low'],
            ['id' => 2, 'name' => 'Medium'],
            ['id' => 3, 'name' => 'High'],
        ];

        foreach ($priorities as $priority) {
            TicketPriority::create($priority);
        }
    }
}
