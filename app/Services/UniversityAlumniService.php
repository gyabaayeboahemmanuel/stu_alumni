<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UniversityAlumniService
{
    private string $baseUrl;
    private string $apiKey;
    private string $authScheme;
    private int $timeoutSeconds;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('university_identity.base_url', 'https://www.stu.edu.gh/identity'), '/');
        $this->apiKey = (string) config('university_identity.api_key', '');
        $this->authScheme = (string) config('university_identity.auth_scheme', 'Bearer');
        $this->timeoutSeconds = (int) config('university_identity.timeout', 60);
    }

    /**
     * Fetch all alumni for an academic year by paging through the upstream API.
     * Returns a simplified payload: state, total, data.
     */
    public function fetchAll(string $academicYear): array
    {
        $page = 1;
        $all = [];
        $lastPayload = [];

        do {
            $payload = $this->postGetAlumni($academicYear, $page, 'all');
            $lastPayload = $payload;

            $pageData = $payload['data'] ?? [];
            if (is_array($pageData)) {
                $all = array_merge($all, $pageData);
            }

            $totalPages = max((int) ($payload['total_pages'] ?? 1), 1);
            $page++;
        } while ($page <= $totalPages);

        return [
            'state' => $lastPayload['state'] ?? 'success',
            'total' => count($all),
            'data' => $all,
        ];
    }

    public function postGetAlumni(string $academicYear, int $page = 1, string|int $limit = 'all'): array
    {
        $requestBody = [
            'acyear' => $academicYear,
            'page' => $page,
            'limit' => $limit,
        ];

        Log::info('UniversityAlumniService outbound request', [
            'endpoint' => $this->baseUrl . '/getAlumni',
            'body' => $requestBody,
        ]);

        $response = $this->http()
            ->asJson()
            ->post($this->baseUrl . '/getAlumni', $requestBody);

        if (!$response->successful()) {
            Log::warning('UniversityAlumniService request failed', [
                'endpoint' => $this->baseUrl . '/getAlumni',
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'state' => 'error',
                'data' => [],
            ];
        }

        $data = $response->json();
        return is_array($data) ? $data : ['state' => 'error', 'data' => []];
    }

    private function http(): PendingRequest
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($this->apiKey !== '') {
            $headers['Authorization'] = trim($this->authScheme . ' ' . $this->apiKey);
        }

        return Http::timeout($this->timeoutSeconds)->withHeaders($headers);
    }
}

