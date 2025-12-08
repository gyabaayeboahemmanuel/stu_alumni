<?php

namespace App\Jobs;

use App\Models\Alumni;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class ExportAlumniDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public $filters = [],
        public $userEmail = null
    ) {}

    public function handle()
    {
        $query = Alumni::with('user');

        // Apply filters
        if (isset($this->filters['verification_status'])) {
            $query->where('verification_status', $this->filters['verification_status']);
        }

        if (isset($this->filters['year_of_completion'])) {
            $query->where('year_of_completion', $this->filters['year_of_completion']);
        }

        if (isset($this->filters['programme'])) {
            $query->where('programme', 'LIKE', "%{$this->filters['programme']}%");
        }

        $alumni = $query->get();

        // Create CSV
        $csv = Writer::createFromString('');
        $csv->insertOne([
            'Student ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Programme',
            'Year of Completion',
            'Verification Status',
            'Current Employer',
            'Job Title',
            'Country',
            'City',
            'Registration Date',
        ]);

        foreach ($alumni as $alumnus) {
            $csv->insertOne([
                $alumnus->student_id,
                $alumnus->first_name,
                $alumnus->last_name,
                $alumnus->email,
                $alumnus->phone,
                $alumnus->programme,
                $alumnus->year_of_completion,
                $alumnus->verification_status,
                $alumnus->current_employer,
                $alumnus->job_title,
                $alumnus->country,
                $alumnus->city,
                $alumnus->created_at->format('Y-m-d'),
            ]);
        }

        $filename = 'alumni-export-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $filePath = 'exports/' . $filename;

        Storage::disk('local')->put($filePath, $csv->toString());

        // TODO: Send email notification with download link
        // if ($this->userEmail) {
        //     Mail::to($this->userEmail)->send(new AlumniExportReady($filePath));
        // }

        return $filePath;
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('Alumni export job failed: ' . $exception->getMessage());
        
        // TODO: Notify admin about failed export
    }
}
