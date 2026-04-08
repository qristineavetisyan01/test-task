<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusIds = LeadStatus::orderBy('order_index')->pluck('id')->all();
        $sourceIds = LeadSource::pluck('id')->all();
        $user = User::first();

        $sampleLeads = [
            ['name' => 'Ava Mitchell', 'email' => 'ava.mitchell@northpeak.io', 'phone' => '+1 202-555-0112', 'company' => 'NorthPeak Labs'],
            ['name' => 'Noah Turner', 'email' => 'noah.turner@blueaxis.com', 'phone' => '+1 202-555-0134', 'company' => 'BlueAxis'],
            ['name' => 'Mia Collins', 'email' => 'mia.collins@orbitcrm.co', 'phone' => '+1 202-555-0177', 'company' => 'Orbit CRM'],
            ['name' => 'Liam Rodriguez', 'email' => 'liam.rodriguez@zenbyte.io', 'phone' => '+1 202-555-0188', 'company' => 'ZenByte'],
            ['name' => 'Sophia Murphy', 'email' => 'sophia.murphy@stratline.ai', 'phone' => '+1 202-555-0196', 'company' => 'StratLine AI'],
            ['name' => 'James Lee', 'email' => 'james.lee@signalworks.co', 'phone' => '+1 202-555-0105', 'company' => 'SignalWorks'],
            ['name' => 'Isabella Scott', 'email' => 'isabella.scott@brightgrid.com', 'phone' => '+1 202-555-0161', 'company' => 'BrightGrid'],
            ['name' => 'Benjamin Harris', 'email' => 'ben.harris@nexacore.io', 'phone' => '+1 202-555-0126', 'company' => 'NexaCore'],
            ['name' => 'Charlotte Young', 'email' => 'charlotte.young@cloudforge.dev', 'phone' => '+1 202-555-0109', 'company' => 'CloudForge'],
            ['name' => 'Lucas Walker', 'email' => 'lucas.walker@velocityhub.com', 'phone' => '+1 202-555-0117', 'company' => 'VelocityHub'],
            ['name' => 'Amelia Hall', 'email' => 'amelia.hall@insightbridge.ai', 'phone' => '+1 202-555-0153', 'company' => 'InsightBridge'],
            ['name' => 'Henry Allen', 'email' => 'henry.allen@riverstoneit.com', 'phone' => '+1 202-555-0101', 'company' => 'Riverstone IT'],
            ['name' => 'Evelyn Wright', 'email' => 'evelyn.wright@quantumlane.io', 'phone' => '+1 202-555-0148', 'company' => 'QuantumLane'],
            ['name' => 'Alexander King', 'email' => 'alex.king@meridianops.com', 'phone' => '+1 202-555-0181', 'company' => 'Meridian Ops'],
            ['name' => 'Harper Green', 'email' => 'harper.green@pixeltrack.co', 'phone' => '+1 202-555-0164', 'company' => 'PixelTrack'],
            ['name' => 'Michael Baker', 'email' => 'michael.baker@uplinklabs.dev', 'phone' => '+1 202-555-0138', 'company' => 'Uplink Labs'],
            ['name' => 'Ella Adams', 'email' => 'ella.adams@novaflow.io', 'phone' => '+1 202-555-0191', 'company' => 'NovaFlow'],
            ['name' => 'Daniel Nelson', 'email' => 'daniel.nelson@shiftdigital.com', 'phone' => '+1 202-555-0122', 'company' => 'Shift Digital'],
            ['name' => 'Scarlett Carter', 'email' => 'scarlett.carter@alphaspark.co', 'phone' => '+1 202-555-0174', 'company' => 'AlphaSpark'],
            ['name' => 'Matthew Perez', 'email' => 'matthew.perez@catalystsuite.com', 'phone' => '+1 202-555-0159', 'company' => 'CatalystSuite'],
        ];

        foreach ($sampleLeads as $index => $lead) {
            Lead::create([
                ...$lead,
                'status_id' => $statusIds[$index % count($statusIds)],
                'source_id' => $sourceIds[$index % count($sourceIds)] ?? null,
                'assigned_to' => $user?->id,
            ]);
        }
    }
}
