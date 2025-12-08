<?php
namespace Database\Seeders;

use App\Models\Executive;
use App\Models\Alumni;
use Illuminate\Database\Seeder;

class ExecutiveSeeder extends Seeder
{
    public function run()
    {
        $alumni = Alumni::limit(5)->get();

        $positions = [
            'President',
            'Vice President',
            'Secretary',
            'Treasurer',
            'Public Relations Officer',
        ];

        $currentYear = date('Y');
        $termYear = $currentYear . '/' . ($currentYear + 1);

        foreach ($positions as $index => $position) {
            if (isset($alumni[$index])) {
                Executive::create([
                    'alumni_id' => $alumni[$index]->id,
                    'position' => $position,
                    'term_year' => $termYear,
                    'start_date' => now()->startOfYear(),
                    'end_date' => now()->addYear()->endOfYear(),
                    'is_current' => true,
                    'display_order' => $index + 1,
                    'bio' => 'Dedicated alumni serving the STU community with passion and commitment.',
                    'achievements' => json_encode([
                        'Led successful alumni initiatives',
                        'Increased member engagement',
                        'Organized community events',
                    ]),
                ]);
            }
        }

        $this->command->info('Executive positions seeded successfully!');
    }
}
