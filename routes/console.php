<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command''s IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Custom commands for STU Alumni System
Artisan::command('alumni:import-sis {file?}', function ($file = null) {
    $this->info('Starting SIS alumni import...');
    
    if ($file) {
        $this->info("Importing from file: {$file}");
    }
    
    // Implementation would go here
    $this->warn('SIS import functionality not implemented yet');
})->purpose('Import alumni data from SIS system');

Artisan::command('alumni:send-newsletter', function () {
    $this->info('Sending newsletter to verified alumni...');
    
    $verifiedAlumni = \App\Models\Alumni::verified()->with('user')->get();
    $this->info("Found {$verifiedAlumni->count()} verified alumni");
    
    // Implementation would go here
    $this->warn('Newsletter functionality not implemented yet');
})->purpose('Send newsletter to all verified alumni');

Artisan::command('alumni:cleanup-pending', function () {
    $this->info('Cleaning up pending registrations older than 30 days...');
    
    $cutoffDate = now()->subDays(30);
    $pendingAlumni = \App\Models\Alumni::pending()
        ->where('created_at', '<', $cutoffDate)
        ->get();
    
    $this->info("Found {$pendingAlumni->count()} pending alumni to cleanup");
    
    foreach ($pendingAlumni as $alumni) {
        $this->line("Deleting pending alumni: {$alumni->full_name}");
        $alumni->delete();
    }
    
    $this->info('Cleanup completed!');
})->purpose('Clean up pending alumni registrations older than 30 days');

Artisan::command('alumni:generate-stats-report', function () {
    $this->info('Generating alumni statistics report...');
    
    $stats = [
        'total_alumni' => \App\Models\Alumni::count(),
        'verified_alumni' => \App\Models\Alumni::verified()->count(),
        'pending_alumni' => \App\Models\Alumni::pending()->count(),
        'by_year' => \App\Models\Alumni::verified()
            ->select('year_of_completion', \DB::raw('COUNT(*) as count'))
            ->groupBy('year_of_completion')
            ->orderBy('year_of_completion')
            ->get()
            ->toArray(),
        'by_programme' => \App\Models\Alumni::verified()
            ->select('programme', \DB::raw('COUNT(*) as count'))
            ->groupBy('programme')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get()
            ->toArray(),
    ];
    
    $filename = 'alumni-stats-' . now()->format('Y-m-d') . '.json';
    file_put_contents(storage_path("app/reports/{$filename}"), json_encode($stats, JSON_PRETTY_PRINT));
    
    $this->info("Report generated: storage/app/reports/{$filename}");
})->purpose('Generate alumni statistics report');
