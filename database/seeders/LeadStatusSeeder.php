<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['New', 'Contacted', 'Qualified', 'Lost'];

        foreach ($statuses as $index => $status) {
            LeadStatus::updateOrCreate(
                ['name' => $status],
                ['order_index' => $index + 1]
            );
        }
    }
}
