<?php

namespace App\Services;

use App\Models\Alumni;
use App\Models\SISIntegration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SISIntegrationService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected bool $isEnabled;

    public function __construct()
    {
        $this->baseUrl = config('services.sis.base_url', '');
        $this->apiKey = config('services.sis.api_key', '');
        $this->isEnabled = config('services.sis.enabled', false);
    }

    public function verifyAlumni(string $studentId, string $dateOfBirth, int $yearOfCompletion): array
    {
        if (!$this->isEnabled) {
            return $this->mockVerification($studentId, $dateOfBirth, $yearOfCompletion);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/verify-alumni', [
                'student_id' => $studentId,
                'date_of_birth' => $dateOfBirth,
                'year_of_completion' => $yearOfCompletion,
            ]);

            $data = $response->json();

            // Log the integration attempt
            SISIntegration::create([
                'student_id' => $studentId,
                'request_data' => [
                    'student_id' => $studentId,
                    'date_of_birth' => $dateOfBirth,
                    'year_of_completion' => $yearOfCompletion,
                ],
                'response_data' => $data,
                'status' => $data['success'] ?? false ? 'success' : 'failed',
                'verified_at' => $data['success'] ?? false ? now() : null,
                'error_message' => $data['message'] ?? null,
            ]);

            if ($response->successful() && ($data['success'] ?? false)) {
                return [
                    'success' => true,
                    'data' => $data['data'],
                    'message' => $data['message'] ?? 'Verification successful',
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Verification failed',
                'error' => $data['error'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('SIS Integration Error: ' . $e->getMessage());

            // Log failed integration
            SISIntegration::create([
                'student_id' => $studentId,
                'request_data' => [
                    'student_id' => $studentId,
                    'date_of_birth' => $dateOfBirth,
                    'year_of_completion' => $yearOfCompletion,
                ],
                'response_data' => ['error' => $e->getMessage()],
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'SIS service temporarily unavailable',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function syncAlumniData(Alumni $alumni): bool
    {
        if (!$this->isEnabled || !$alumni->student_id) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/api/alumni/' . $alumni->student_id);

            if ($response->successful()) {
                $data = $response->json();
                
                // Update alumni record with latest data from SIS
                $alumni->update([
                    'first_name' => $data['first_name'] ?? $alumni->first_name,
                    'last_name' => $data['last_name'] ?? $alumni->last_name,
                    'programme' => $data['programme'] ?? $alumni->programme,
                    // Add other fields as needed
                ]);

                return true;
            }

            return false;

        } catch (\Exception $e) {
            Log::error('SIS Sync Error for alumni ' . $alumni->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function batchVerifyAlumni(array $studentIds): array
    {
        $results = [];

        foreach ($studentIds as $studentId) {
            $results[$studentId] = $this->verifyAlumni($studentId, '', date('Y'));
        }

        return $results;
    }

    private function mockVerification(string $studentId, string $dateOfBirth, int $yearOfCompletion): array
    {
        // Mock data for development/demo purposes
        $mockData = [
            'student_id' => $studentId,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'other_names' => fake()->optional()->firstName(),
            'gender' => fake()->randomElement(['male', 'female']),
            'programme' => fake()->randomElement([
                'BSc. Computer Science',
                'BSc. Information Technology',
                'BSc. Electrical Engineering',
                'BSc. Mechanical Engineering',
                'BSc. Business Administration',
            ]),
            'qualification' => 'Bachelor',
            'year_of_completion' => $yearOfCompletion,
        ];

        // Simulate API delay
        sleep(1);

        // Log mock verification
        SISIntegration::create([
            'student_id' => $studentId,
            'request_data' => [
                'student_id' => $studentId,
                'date_of_birth' => $dateOfBirth,
                'year_of_completion' => $yearOfCompletion,
            ],
            'response_data' => ['mock_data' => $mockData],
            'status' => 'success',
            'verified_at' => now(),
        ]);

        return [
            'success' => true,
            'data' => $mockData,
            'message' => 'Mock verification successful (SIS integration disabled)',
        ];
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}
