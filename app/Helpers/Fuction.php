<?php

namespace App\Helpers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Fuction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Pull student data from school management system
     * Uses EXACT same logic as IRMTS Fuction helper (matching school server implementation)
     * Alumni system ONLY handles students, not staff
     * 
     * @param string $student_id Student ID, email, or phone number
     * @param string $entity Entity type: 'student' (only students are supported in Alumni system)
     * @return array|int Response data or 0 on failure
     */
    public static function pullData($student_id, $entity)
    {
        // EXACT COPY of IRMTS implementation - do not modify the request format
        $client = new Client();
        $token = config('app.remote_secret');
        
        // Try both URLs in sequence:
        // 1. First: https://stu.edu.gh/identity/secure_staff_std (default/config)
        // 2. Second: https://stu.edu.gh/identity/verify_connect_api (from .env REMOTE)
        $remoteUrls = [
            'https://stu.edu.gh/identity/secure_staff_std',  // Default URL
            env('REMOTE', 'https://stu.edu.gh/identity/verify_connect_api'),  // From .env
        ];
        
        // Remove duplicates while preserving order
        $remoteUrls = array_values(array_unique($remoteUrls));
        
        \Log::info('Starting verification with multiple URLs', [
            'urls_to_try' => $remoteUrls,
            'total_urls' => count($remoteUrls),
            'identifier' => $student_id,
        ]);
        
        $data = [
            'index_staff_id' => $student_id,
            'entity' => $entity,
        ];

        // Try each URL until one works - log each attempt clearly
        $lastError = null;
        foreach ($remoteUrls as $index => $remote) {
            $attemptNumber = $index + 1;
            
            \Log::info("=== Attempt {$attemptNumber}: Verifying with {$remote} ===", [
                'attempt' => $attemptNumber,
                'url' => $remote,
                'identifier' => $student_id,
                'entity' => $entity,
            ]);

            try {
                $response = Http::timeout(30)->withOptions(['verify' => false])
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                    ])
                    ->asForm()
                    ->post($remote, $data);

                $responseData = json_decode($response->body(), true);
                
                // Log the response for this attempt
                \Log::info("Attempt {$attemptNumber} Response", [
                    'url' => $remote,
                    'http_status' => $response->status(),
                    'response_data' => $responseData,
                    'response_body' => $response->body(),
                ]);
                
                if ($responseData) {
                    // Check response status
                    if (isset($responseData['status']) && $responseData['status'] == 200) {
                        \Log::info("✓ SUCCESS: Attempt {$attemptNumber} succeeded with URL: {$remote}");
                        return $responseData;
                    } elseif (isset($responseData['status']) && $responseData['status'] == 404) {
                        // Student not found - this is a valid response, but try next URL if available
                        $errorMsg = $responseData['detail']['state'] ?? 'Student not found';
                        \Log::warning("✗ Attempt {$attemptNumber} Error: {$errorMsg}", [
                            'url' => $remote,
                            'status' => 404,
                            'error_message' => $errorMsg,
                        ]);
                        $lastError = $responseData;
                        
                        // If this is not the last URL, try the next one
                        if ($index < count($remoteUrls) - 1) {
                            continue;
                        }
                    } elseif (isset($responseData['status']) && $responseData['status'] == 401) {
                        // Authorization error - log and try next URL
                        $errorMsg = $responseData['detail'] ?? 'Invalid authorization';
                        \Log::warning("✗ Attempt {$attemptNumber} Authorization Error: {$errorMsg}", [
                            'url' => $remote,
                            'status' => 401,
                            'error_message' => $errorMsg,
                        ]);
                        $lastError = $responseData;
                        
                        // If this is not the last URL, try the next one
                        if ($index < count($remoteUrls) - 1) {
                            continue;
                        }
                    } else {
                        // Other error - log and try next URL
                        $errorMsg = $responseData['detail']['state'] ?? 'Unknown error';
                        \Log::warning("✗ Attempt {$attemptNumber} Error: {$errorMsg}", [
                            'url' => $remote,
                            'status' => $responseData['status'] ?? 'unknown',
                            'error_message' => $errorMsg,
                        ]);
                        $lastError = $responseData;
                        
                        // If this is not the last URL, try the next one
                        if ($index < count($remoteUrls) - 1) {
                            continue;
                        }
                    }
                } else {
                    \Log::warning("✗ Attempt {$attemptNumber} Error: Invalid response format", [
                        'url' => $remote,
                        'response_body' => $response->body(),
                    ]);
                    $lastError = ['status' => 500, 'detail' => ['state' => 'Invalid response format']];
                }
            } catch (\Exception $e) {
                \Log::error("✗ Attempt {$attemptNumber} Exception: {$e->getMessage()}", [
                    'url' => $remote,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $lastError = ['status' => 500, 'detail' => ['state' => $e->getMessage()]];
                
                // If this is not the last URL, try the next one
                if ($index < count($remoteUrls) - 1) {
                    continue;
                }
            }
        }
        
        // All URLs failed - return the last error or 0
        \Log::error('✗ All URLs failed - returning last error', [
            'identifier' => $student_id,
            'entity' => $entity,
            'urls_tried' => $remoteUrls,
            'last_error' => $lastError,
        ]);
        
        // Return the last error response if available, otherwise return 0
        return $lastError ?? 0;
    }

    /**
     * Verify if a student exists in school system
     * This is the primary method used by the Alumni system
     * 
     * @param string $studentId Student ID, email, or phone number
     * @return array|int Student data or 0 on failure
     */
    public static function verifyStudent($studentId)
    {
        return self::pullData($studentId, 'student');
    }
}