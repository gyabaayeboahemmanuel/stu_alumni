@extends('layouts.admin')

@section('title', 'Past Student List')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Past Student List</h1>
            <p class="text-gray-600 mt-2">Fetch graduated alumni by academic year</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-2">
                <label for="academic_year" class="form-label">Academic Year (Graduation Year)</label>
                <select id="academic_year" class="form-input" aria-label="Academic year">
                    <option value="">Select a year</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button id="fetch_past_students" type="button" class="btn-primary w-full">
                    <i class="fas fa-search mr-2"></i>Fetch
                </button>
            </div>

            <div class="md:col-span-1">
                <div id="fetch_status" class="text-sm text-gray-600 hidden">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Fetching…
                </div>
            </div>
        </div>
    </div>

    <div id="past_students_results" class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 id="results_title" class="text-lg font-semibold text-gray-900">
                Alumni results
            </h2>
        </div>

        <div class="overflow-x-auto">
            <div id="results_body" class="p-6 text-sm text-gray-600">
                Select an academic year and click <strong>Fetch</strong>.
            </div>
        </div>
    </div>
</div>

<script>
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function normalizeAlumniPayload(payload) {
        if (Array.isArray(payload)) return payload;
        if (!payload || typeof payload !== 'object') return [];
        if (Array.isArray(payload.data)) return payload.data;
        if (Array.isArray(payload.alumni)) return payload.alumni;
        if (Array.isArray(payload.students)) return payload.students;
        if (Array.isArray(payload.results)) return payload.results;
        return [];
    }

    document.addEventListener('DOMContentLoaded', function () {
        const yearSelect = document.getElementById('academic_year');
        const fetchBtn = document.getElementById('fetch_past_students');
        const statusEl = document.getElementById('fetch_status');
        const resultsBody = document.getElementById('results_body');
        const resultsTitle = document.getElementById('results_title');

        fetchBtn?.addEventListener('click', async () => {
            const academicYear = yearSelect?.value;

            if (!academicYear) {
                resultsBody.innerHTML = '<div class="text-red-600">Please select an academic year.</div>';
                return;
            }

            statusEl?.classList.remove('hidden');
            resultsBody.innerHTML = '<div class="text-gray-600">Fetching alumni…</div>';

            try {
                const response = await fetch(
                    '{{ route('admin.past-students.fetch') }}?academic_year=' + encodeURIComponent(academicYear),
                    { headers: { 'Accept': 'application/json' } }
                );

                const payload = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const msg = payload?.error || 'Request failed.';
                    resultsBody.innerHTML = '<div class="text-red-600">' + escapeHtml(msg) + '</div>';
                    return;
                }

                const alumni = normalizeAlumniPayload(payload);
                resultsTitle.textContent = 'Alumni results (' + alumni.length + ')';

                if (alumni.length === 0) {
                    resultsBody.innerHTML = '<div class="text-gray-600">No alumni found for ' + escapeHtml(academicYear) + '.</div>';
                    return;
                }

                const rowsHtml = alumni.map(a => {
                    const name = a.full_name || a.name || (
                        (a.first_name || '') + ' ' + (a.last_name || '')
                    ).trim();

                    const year = a.year_of_completion || a.academic_year || a.graduation_year || '';
                    const programme = a.programme || a.program || a.course || '';
                    const status = a.verification_status || a.status || '';

                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${escapeHtml(name || 'N/A')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(a.student_id || 'N/A')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(programme || 'N/A')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(year || 'N/A')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(a.email || 'N/A')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(a.phone || 'N/A')}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                ${status ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">' + escapeHtml(status) + '</span>' : 'N/A'}
                            </td>
                        </tr>
                    `;
                }).join('');

                resultsBody.innerHTML = `
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programme</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${rowsHtml}
                        </tbody>
                    </table>
                `;
            } catch (e) {
                resultsBody.innerHTML = '<div class="text-red-600">Unexpected error: ' + escapeHtml(e?.message || String(e)) + '</div>';
            } finally {
                statusEl?.classList.add('hidden');
            }
        });
    });
</script>
@endsection

