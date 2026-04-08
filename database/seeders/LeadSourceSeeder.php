<?php

namespace Database\Seeders;

use App\Models\LeadSource;
use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Website', 'Facebook', 'Referral'] as $source) {
            LeadSource::updateOrCreate(['name' => $source]);
        }
    }
}
