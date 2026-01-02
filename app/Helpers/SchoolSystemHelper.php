<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SchoolSystemHelper
{
    /**
     * Pull student data from school management system
     * Uses the same pattern as IRMTS Fuction helper
     * Alumni system ONLY handles students, not staff
     * 
     * @param string $identifier Student ID, email, or phone number
     * @param string $entity Entity type: 'student' (only students are supported in Alumni system)
     * @return array|int Response data or 0 on failure
     */
    public static function pullData($identifier, $entity = 'student')
    {
        $token = config('app.remote_secret', env('CODE', ''));
        $remote = config('app.remote_url', env('REMOTE', 'https://stu.edu.gh/identity/secure_staff_std'));
        
        if (!$token || !$remote) {
            Log::warning('School system API not configured', [
                'has_token' => !empty($token),
                'has_url' => !empty($remote),
            ]);
            return 0;
        }

        $data = [
            'index_staff_id' => $identifier,
            'entity' => $entity,
        ];

        try {
            $response = Http::timeout(30)->withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])
                ->asForm()
                ->post($remote, $data);

            $responseData = json_decode($response->body(), true);
            
            if ($responseData && isset($responseData['status'])) {
                return $responseData;
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::error('School system API request failed', [
                'error' => $e->getMessage(),
                'identifier' => $identifier,
                'entity' => $entity,
            ]);
            return 0;
        }
    }

    /**
     * Verify student data from school system
     * 
     * @param string $studentId Student ID
     * @return array|int Student data or 0 on failure
     */
    public static function verifyStudent($studentId)
    {
        return self::pullData($studentId, 'student');
    }
}

