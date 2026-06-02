<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UniversityAlumniService
{
    private string $endpoint;
    private string $apiKey;
    private int $timeoutSeconds;

    public function __construct()
    {
        $baseUrl = rtrim(config('university_identity.base_url', 'https://www.stu.edu.gh/identity'), '/');
        $path = config('university_identity.get_alumni_path', '/getAlumni.php');
        $this->endpoint = $baseUrl . $path;
        $this->apiKey = (string) config('university_identity.api_key', '');
        $this->timeoutSeconds = (int) config('university_identity.timeout', 60);
    }

    /**
     * Fetch alumni for an academic year (pages through total_pages when needed).
     */
    public function fetchAll(string $academicYear, int $limit = 1): array
    {
        $page = 1;
        $all = [];
        $lastPayload = [];

        do {
            $result = $this->postGetAlumni($academicYear, $page, $limit);
            if (!$result['success']) {
                return $result;
            }

            $payload = $result['payload'];
            $lastPayload = $payload;

            $pageData = $payload['data'] ?? [];
            if (is_array($pageData)) {
                $all = array_merge($all, $pageData);
            }

            $totalPages = max((int) ($payload['total_pages'] ?? 1), 1);
            $page++;
        } while ($page <= $totalPages);

        return [
            'success' => true,
            'state' => $lastPayload['state'] ?? 'success',
            'total' => count($all),
            'data' => $all,
            'message' => count($all) === 0
                ? ($lastPayload['state'] ?? 'No alumni found for this academic year.')
                : null,
        ];
    }

    public function postGetAlumni(string $academicYear, int $page = 1, int|string $limit = 1): array
    {
        $requestBody = [
            'acyear' => $academicYear,
            'page' => $page,
            'limit' => $limit,
        ];

        Log::info('UniversityAlumniService outbound request', [
            'endpoint' => $this->endpoint,
            'body' => $requestBody,
        ]);

        $response = $this->http()
            ->asJson()
            ->post($this->endpoint, $requestBody);

        if (!$response->successful()) {
            Log::warning('UniversityAlumniService HTTP request failed', [
                'endpoint' => $this->endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'University API request failed (HTTP ' . $response->status() . ').',
                'data' => [],
            ];
        }

        $raw = $response->json();
        if (!is_array($raw)) {
            return [
                'success' => false,
                'message' => 'University API returned an invalid response.',
                'data' => [],
            ];
        }

        $payload = $this->normalizePayload($raw);
        $apiStatus = (int) ($raw['status'] ?? 200);
        $records = $payload['data'] ?? [];

        Log::info('UniversityAlumniService response', [
            'endpoint' => $this->endpoint,
            'api_status' => $apiStatus,
            'state' => $payload['state'] ?? null,
            'total' => $payload['total'] ?? count($records),
        ]);

        if ($apiStatus === 401) {
            $detail = is_string($raw['detail'] ?? null)
                ? $raw['detail']
                : ($payload['state'] ?? 'Authorization failed.');

            return [
                'success' => false,
                'message' => $detail,
                'data' => [],
            ];
        }

        if ($apiStatus === 404 || empty($records)) {
            return [
                'success' => true,
                'payload' => $payload,
                'message' => $payload['state'] ?? 'No alumni found for this academic year.',
            ];
        }

        return [
            'success' => true,
            'payload' => $payload,
            'message' => null,
        ];
    }

    /**
     * Identity API often wraps results: { status, desc, detail: { state, data, ... } }
     */
    private function normalizePayload(array $raw): array
    {
        if (isset($raw['detail']) && is_array($raw['detail'])) {
            return $raw['detail'];
        }

        return $raw;
    }

    private function http(): PendingRequest
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($this->apiKey !== '') {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        return Http::timeout($this->timeoutSeconds)
            ->withOptions(['verify' => false])
            ->withHeaders($headers);
    }
}
