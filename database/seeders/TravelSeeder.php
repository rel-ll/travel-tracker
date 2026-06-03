<?php

namespace Database\Seeders;

use App\Models\Travel;
use Illuminate\Database\Seeder;

class TravelSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'travel_mode' => 'air',
                'origin'      => 'Manila',
                'destination' => 'Cebu',
                'purpose'     => 'Q2 Site Audit',
                'amount'      => 12500.00,
                'passengers'  => 3,
                'travel_date' => '2025-06-10',
                'return_date' => '2025-06-13',
                'notes'       => 'Audit of Cebu manufacturing plant.',
            ],
            [
                'travel_mode' => 'sea',
                'origin'      => 'Cebu',
                'destination' => 'Bohol',
                'purpose'     => 'Team Building 2025',
                'amount'      => 45000.00,
                'passengers'  => 15,
                'travel_date' => '2025-07-05',
                'return_date' => '2025-07-07',
                'notes'       => 'Company team building activity.',
            ],
            [
                'travel_mode' => 'land',
                'origin'      => 'Makati',
                'destination' => 'Tagaytay',
                'purpose'     => 'Client Meeting - ABC Corp',
                'amount'      => 3200.00,
                'passengers'  => 2,
                'travel_date' => '2025-07-20',
                'return_date' => null,
                'notes'       => 'Day trip for project kick-off.',
            ],
        ];

        foreach ($records as $record) {
            Travel::create($record);
        }
    }
}
