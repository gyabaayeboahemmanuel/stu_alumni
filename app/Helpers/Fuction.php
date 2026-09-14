<?php

namespace App\Helpers;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * Uses the same pattern as IRMTS Fuction helper
     * Alumni system ONLY handles students, not staff
     *
     * @param string $student_id Student ID, email, or phone number
     * @param string $entity Entity type: 'student' (only students are supported in Alumni system)
     * @return array|int Response data or 0 on failure
     */
    public static function pullData($student_id, $entity)
    {
        $token = config('app.remote_secret');
        $configuredRemote = rtrim((string) config('app.remote_url'), '/');

        // Prefer configured URL; fall back to the current identity host if REMOTE
        // still points at the retired stu.edu.gh/identity/* paths.
        $remoteUrls = array_values(array_unique(array_filter([
            $configuredRemote,
            'https://identity.stu.edu.gh/secure_staff_std.php',
            'https://identity.stu.edu.gh/secure_staff_std',
        ])));

        Log::info('Starting SIS verification', [
            'urls_to_try' => $remoteUrls,
            'identifier' => $student_id,
            'entity' => $entity,
        ]);

        $data = [
            'index_staff_id' => $student_id,
            'entity' => $entity,
        ];

        $lastError = null;
        foreach ($remoteUrls as $index => $remote) {
            $attemptNumber = $index + 1;

            Log::info("SIS verification attempt {$attemptNumber}", [
                'url' => $remote,
                'identifier' => $student_id,
                'entity' => $entity,
            ]);

            try {
                $response = Http::timeout(30)->withOptions(['verify' => false])
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                    ])
                    ->asForm()
                    ->post($remote, $data);

                $body = $response->body();
                $responseData = json_decode($body, true);

                Log::info("SIS verification attempt {$attemptNumber} response", [
                    'url' => $remote,
                    'http_status' => $response->status(),
                    'content_type' => $response->header('Content-Type'),
                    'response_data' => is_array($responseData) ? $responseData : null,
                    'body_preview' => is_array($responseData) ? null : substr($body, 0, 300),
                ]);

                if (is_array($responseData) && isset($responseData['status'])) {
                    // Valid school-server JSON (success or not-found). Prefer a host
                    // that actually answered over older dead endpoints.
                    if (
                        (int) $responseData['status'] === 200
                        || (isset($responseData['desc']) && $responseData['desc'] === 'request success')
                    ) {
                        Log::info("SIS verification succeeded via {$remote}", [
                            'status' => $responseData['status'],
                        ]);
                        return $responseData;
                    }

                    $lastError = $responseData;

                    if ($index < count($remoteUrls) - 1) {
                        continue;
                    }

                    return $responseData;
                }

                $lastError = [
                    'status' => 500,
                    'detail' => ['state' => 'Invalid response format from school identity server'],
                ];
            } catch (\Exception $e) {
                Log::error("SIS verification attempt {$attemptNumber} exception", [
                    'url' => $remote,
                    'error' => $e->getMessage(),
                ]);
                $lastError = [
                    'status' => 500,
                    'detail' => ['state' => $e->getMessage()],
                ];

                if ($index < count($remoteUrls) - 1) {
                    continue;
                }
            }
        }

        Log::error('SIS verification failed for all identity URLs', [
            'identifier' => $student_id,
            'entity' => $entity,
            'urls_tried' => $remoteUrls,
            'last_error' => $lastError,
        ]);

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
