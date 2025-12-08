<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class Fuction
{
    /**
     * Pull data from SIS API with comprehensive logging
     */
    public static function pullData($staff_std_id, $entity)
    {
        // Use config values
        $baseUrl = config('sis.base_url');
        $apiKey = config('sis.api_key');
        $timeout = config('sis.timeout');
        $isEnabled = config('sis.enabled');

        // Log verification attempt
        Log::channel('sis')->info('SIS Verification Attempt', [
            'student_id' => $staff_std_id,
            'entity' => $entity,
            'timestamp' => now()->toDateTimeString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // If SIS is disabled, return mock data for development
        if (!$isEnabled) {
            return self::getMockData($staff_std_id, $entity);
        }

        $data = [
            'index_staff_id' => $staff_std_id,
            'entity' => $entity,
        ];

        try {
            // Log API request details
            Log::channel('sis')->debug('SIS API Request Details', [
                'url' => $baseUrl,
                'payload' => $data,
                'headers' => [
                    'Authorization' => 'Bearer ' . substr($apiKey, 0, 10) . '...', // Log partial key for security
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            $startTime = microtime(true);
            
            $response = Http::timeout($timeout)
                ->withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post($baseUrl, $data);

            $responseTime = round((microtime(true) - $startTime) * 1000, 2); // Response time in ms

            // Get raw response body for logging
            $rawResponse = $response->body();
            $responseData = $response->json();

            // Comprehensive logging of API response
            Log::channel('sis')->info('SIS API Response', [
                'student_id' => $staff_std_id,
                'entity' => $entity,
                'http_status' => $response->status(),
                'response_time_ms' => $responseTime,
                'response_size' => strlen($rawResponse),
                'raw_response' => $rawResponse,
                'parsed_response' => $responseData,
                'success' => $response->successful()
            ]);

            if ($response->successful()) {
                // Log successful verification
                Log::channel('sis')->notice('SIS Verification Success', [
                    'student_id' => $staff_std_id,
                    'entity' => $entity,
                    'response_data_keys' => array_keys($responseData),
                    'has_email' => !empty($responseData['email']),
                    'has_name' => !empty($responseData['full_name']) || (!empty($responseData['first_name']) && !empty($responseData['last_name']))
                ]);

                return [
                    'success' => true,
                    'data' => $responseData,
                    'message' => 'Data retrieved successfully',
                    'response_time' => $responseTime
                ];
            } else {
                // Log API error response
                Log::channel('sis')->warning('SIS API Error', [
                    'student_id' => $staff_std_id,
                    'entity' => $entity,
                    'http_status' => $response->status(),
                    'error_message' => $responseData['message'] ?? 'No error message provided',
                    'response_data' => $responseData
                ]);

                return [
                    'success' => false,
                    'data' => $responseData,
                    'message' => $responseData['message'] ?? 'SIS API returned error: ' . $response->status(),
                    'response_time' => $responseTime
                ];
            }

        } catch (Exception $e) {
            // Log connection failures
            Log::channel('sis')->error('SIS API Connection Failed', [
                'student_id' => $staff_std_id,
                'entity' => $entity,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $baseUrl,
                'timestamp' => now()->toDateTimeString()
            ]);

            return [
                'success' => false,
                'data' => null,
                'message' => 'Connection to SIS failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mock data for development when SIS is disabled
     */
    private static function getMockData($staff_std_id, $entity)
    {
        // Simulate API delay
        if (config('sis.mock_data.enabled')) {
            sleep(config('sis.mock_data.response_delay'));
        }

        // Mock response based on student ID pattern
        $mockData = [
            'status' => 200,
            'message' => 'Success',
            'data' => [
                'full_name' => 'John Doe',
                'email' => strtolower($staff_std_id) . '@stu.edu.gh',
                'student_id' => $staff_std_id,
                'programme' => 'BSc. Computer Science',
                'graduation_year' => '2020',
                'current_employer' => 'Tech Company Ltd',
                'job_title' => 'Software Developer',
                'phone' => '+233 24 123 4567',
                'entity' => $entity,
            ]
        ];

        Log::channel('sis')->info('Using mock SIS data', [
            'student_id' => $staff_std_id,
            'entity' => $entity,
            'mock_data' => $mockData,
            'note' => 'SIS is disabled in configuration'
        ]);

        return [
            'success' => true,
            'data' => $mockData,
            'message' => 'Mock data retrieved (SIS disabled)'
        ];
    }

    /**
     * Verify if a student exists in SIS
     */
    public static function verifyStudent($studentId)
    {
        return self::pullData($studentId, 'student');
    }

    /**
     * Verify if staff exists in SIS
     */
    public static function verifyStaff($staffId)
    {
        return self::pullData($staffId, 'staff');
    }

    /**
     * Batch verify multiple IDs
     */
    public static function batchVerify(array $ids, $entity = 'student')
    {
        $results = [];
        foreach ($ids as $id) {
            $results[$id] = self::pullData($id, $entity);
        }
        return $results;
    }
}