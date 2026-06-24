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
                <label for="academic_year" class="form-label">Academic Year</label>
                <select id="academic_year" class="form-input" aria-label="Academic year">
                    <option value="">Select an academic year</option>
                    @foreach($academicYears as $academicYear)
                        <option value="{{ $academicYear }}">{{ $academicYear }}</option>
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

    <div id="past_students_results" class="card overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 id="results_title" class="text-lg font-semibold text-gray-900">
                Alumni results
            </h2>
            <div id="results_search_wrap" class="hidden w-full sm:w-auto sm:min-w-[280px]">
                <label for="results_search" class="sr-only">Search loaded list</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input
                        type="search"
                        id="results_search"
                        class="form-input pl-9 pr-9 w-full sm:w-72"
                        placeholder="Search name, ID, programme, email…"
                        autocomplete="off"
                    >
                    <button
                        type="button"
                        id="results_search_clear"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden"
                        title="Clear search"
                        aria-label="Clear search"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <div id="results_body" class="p-6 text-sm text-gray-600">
                Select an academic year and click <strong>Fetch</strong>.
            </div>
        </div>

        <div id="results_pagination" class="px-6 py-4 border-t border-gray-200 hidden">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p id="pagination_summary" class="text-sm text-gray-600"></p>
                <div class="flex items-center gap-2">
                    <button type="button" id="pagination_prev" class="btn-secondary text-sm px-3 py-1.5" disabled>
                        <i class="fas fa-chevron-left mr-1"></i>Previous
                    </button>
                    <span id="pagination_page_label" class="text-sm text-gray-700 px-2"></span>
                    <button type="button" id="pagination_next" class="btn-secondary text-sm px-3 py-1.5" disabled>
                        Next<i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(config('app.debug'))
    <div id="past_students_debug" class="card overflow-hidden hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-bug mr-2 text-amber-600"></i>API Debug
            </h2>
            <span class="text-xs text-gray-500">Visible because APP_DEBUG=true</span>
        </div>
        <div class="p-6 space-y-4 text-sm bg-white">
            <div id="debug_summary" class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-950 hidden"></div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Request sent</h3>
                <pre id="debug_request" class="past-students-debug-pre"></pre>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">Response received</h3>
                <pre id="debug_response" class="past-students-debug-pre"></pre>
            </div>
        </div>
    </div>

    <style>
        .past-students-debug-pre {
            display: block;
            background: #f8fafc !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            font-size: 12px;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
    </style>
    @endif
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

    function interpretApiDebug(payload) {
        const dbg = payload?.debug ?? {};
        const uni = dbg.university ?? dbg;
        const uniResponse = uni.university_response ?? uni;
        const detail = uniResponse.detail ?? {};
        const httpStatus = uni.university_http_status ?? dbg.university_http_status;
        const apiStatus = uniResponse.status ?? uni.status;

        let apiStatusMeaning = 'See full response below.';
        if (apiStatus === 401) {
            apiStatusMeaning = 'Authorization failed — check CODE token in .env.';
        } else if (apiStatus === 404) {
            apiStatusMeaning = 'No alumni records for the selected academic year. This is NOT a broken URL or HTTP 404 page.';
        } else if (apiStatus === 200 || uniResponse.state === 'success' || payload?.state === 'success') {
            apiStatusMeaning = 'Records returned successfully.';
        }

        if (dbg.from_cache) {
            apiStatusMeaning += ' (page served from cache)';
        }

        return {
            http_status: httpStatus,
            connection: httpStatus === 200 ? 'OK — university server reached' : 'Failed to reach server',
            authorization: apiStatus === 401 ? 'Failed' : 'OK — Bearer token accepted',
            api_status: apiStatus,
            api_status_meaning: apiStatusMeaning,
            desc: uniResponse.desc ?? uni.desc ?? null,
            requested_acyear: payload?.acyear ?? uni.university_request?.acyear ?? dbg.university_request?.acyear ?? null,
            current_acyear_on_server: detail.current_acyear ?? uniResponse.current_acyear ?? uni.current_acyear ?? null,
            limit_sent: uni.limit_sent ?? dbg.limit_sent ?? null,
            limit_returned: uni.limit_returned ?? dbg.limit_returned ?? null,
            total_from_api: payload?.total ?? detail.total ?? uniResponse.total ?? uni.total ?? null,
        };
    }

    function renderDebug(appRequest, payload) {
        const debugPanel = document.getElementById('past_students_debug');
        const debugSummary = document.getElementById('debug_summary');
        const debugRequest = document.getElementById('debug_request');
        const debugResponse = document.getElementById('debug_response');

        if (!debugPanel || !debugRequest || !debugResponse) {
            return;
        }

        const interpretation = interpretApiDebug(payload);
        const sent = {
            interpretation: interpretation,
            app_request: appRequest,
            university: payload?.debug?.university ?? payload?.debug ?? null,
            from_cache: payload?.debug?.from_cache ?? false,
        };

        if (debugSummary) {
            debugSummary.innerHTML = `
                <p class="font-semibold mb-2">How to read this response</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>HTTP ${escapeHtml(interpretation.http_status)}</strong> — ${escapeHtml(interpretation.connection)}</li>
                    <li><strong>Authorization</strong> — ${escapeHtml(interpretation.authorization)}</li>
                    <li><strong>API status ${escapeHtml(interpretation.api_status)}</strong> — ${escapeHtml(interpretation.api_status_meaning)}</li>
                    <li><strong>Requested acyear</strong> — ${escapeHtml(interpretation.requested_acyear || 'N/A')}</li>
                    <li><strong>Server current acyear</strong> — ${escapeHtml(interpretation.current_acyear_on_server || 'N/A')}</li>
                    <li><strong>Limit</strong> — sent ${escapeHtml(JSON.stringify(interpretation.limit_sent))}, API returned ${escapeHtml(JSON.stringify(interpretation.limit_returned))}</li>
                </ul>
            `;
            debugSummary.classList.remove('hidden');
        }

        debugRequest.textContent = JSON.stringify(sent, null, 2);
        debugResponse.textContent = JSON.stringify(payload, null, 2);
        debugPanel.classList.remove('hidden');

        console.group('Past Student List — API Debug');
        console.log('Request sent:', sent);
        console.log('Response received:', payload);
        console.groupEnd();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const yearSelect = document.getElementById('academic_year');
        const fetchBtn = document.getElementById('fetch_past_students');
        const statusEl = document.getElementById('fetch_status');
        const resultsBody = document.getElementById('results_body');
        const resultsTitle = document.getElementById('results_title');
        const paginationPanel = document.getElementById('results_pagination');
        const paginationSummary = document.getElementById('pagination_summary');
        const paginationPageLabel = document.getElementById('pagination_page_label');
        const paginationPrev = document.getElementById('pagination_prev');
        const paginationNext = document.getElementById('pagination_next');
        const searchWrap = document.getElementById('results_search_wrap');
        const searchInput = document.getElementById('results_search');
        const searchClearBtn = document.getElementById('results_search_clear');

        const fetchRoute = '{{ route('admin.past-students.fetch') }}';
        let currentAcademicYear = '';
        let currentPage = 1;
        let currentSearch = '';
        let searchDebounceTimer = null;

        function buildFetchUrl(academicYear, page, refresh, search) {
            const params = new URLSearchParams({
                academic_year: academicYear,
                page: String(page),
            });
            if (refresh) {
                params.set('refresh', '1');
            }
            if (search) {
                params.set('search', search);
            }
            return fetchRoute + '?' + params.toString();
        }

        function updateSearchControls() {
            const hasSearch = currentSearch.length > 0;
            searchClearBtn?.classList.toggle('hidden', !hasSearch);
        }

        function buildResultsTitle(total, totalInList, search) {
            if (search) {
                return 'Alumni results (' + total + ' of ' + totalInList + ' matching)';
            }
            return 'Alumni results (' + total + ')';
        }

        function updatePaginationControls(pagination, total, totalInList, search) {
            if (!pagination || total === 0) {
                paginationPanel?.classList.add('hidden');
                return;
            }

            paginationPanel?.classList.remove('hidden');
            const scope = search
                ? pagination.from + '–' + pagination.to + ' of ' + total + ' matches'
                : pagination.from + '–' + pagination.to + ' of ' + totalInList;
            paginationSummary.textContent = 'Showing ' + scope;
            paginationPageLabel.textContent = 'Page ' + pagination.current_page + ' of ' + pagination.last_page;
            paginationPrev.disabled = pagination.current_page <= 1;
            paginationNext.disabled = pagination.current_page >= pagination.last_page;
        }

        function renderAlumniTable(alumni, total, totalInList, pagination, search) {
            resultsTitle.textContent = buildResultsTitle(total, totalInList, search);
            updatePaginationControls(pagination, total, totalInList, search);
            searchWrap?.classList.remove('hidden');

            if (alumni.length === 0) {
                return false;
            }

            const rowOffset = (pagination?.from ?? 1) - 1;

            const rowsHtml = alumni.map((a, index) => {
                const sn = rowOffset + index + 1;
                const name = a.fullname || a.full_name || a.name || (
                    (a.first_name || '') + ' ' + (a.last_name || '')
                ).trim();

                const year = a.acyear || a.acc_year || a.year_of_completion || a.academic_year || a.graduation_year || '';
                const programme = a.programme || a.program || a.course || '';
                const status = a.final_remarks || a.verification_status || a.status || '';
                const studentId = a.index_number || a.student_id || '';

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 tabular-nums">${sn}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${escapeHtml(name || 'N/A')}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${escapeHtml(studentId || 'N/A')}</td>
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">SN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programme</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Academic Year</th>
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

            return true;
        }

        async function loadAlumniPage(academicYear, page, refresh, search) {
            currentAcademicYear = academicYear;
            currentPage = page;
            currentSearch = search ?? currentSearch;

            statusEl?.classList.remove('hidden');
            let loadingMsg = 'Loading page ' + page + '…';
            if (refresh) {
                loadingMsg = 'Fetching alumni from university…';
            } else if (currentSearch) {
                loadingMsg = 'Searching…';
            }
            resultsBody.innerHTML = '<div class="text-gray-600">' + loadingMsg + '</div>';

            const fetchUrl = buildFetchUrl(academicYear, page, refresh, currentSearch);
            const appRequest = {
                method: 'GET',
                url: fetchUrl,
                query: Object.fromEntries(new URL(fetchUrl, window.location.origin).searchParams),
            };

            try {
                const response = await fetch(fetchUrl, { headers: { 'Accept': 'application/json' } });
                const payload = await response.json().catch(() => ({}));
                renderDebug(appRequest, payload);

                if (!response.ok) {
                    const msg = payload?.error || 'Request failed.';
                    resultsBody.innerHTML = '<div class="text-red-600">' + escapeHtml(msg) + '</div>';
                    resultsTitle.textContent = 'Alumni results (error)';
                    paginationPanel?.classList.add('hidden');
                    searchWrap?.classList.add('hidden');
                    return;
                }

                const alumni = normalizeAlumniPayload(payload);
                const total = payload?.total ?? alumni.length;
                const totalInList = payload?.total_in_list ?? total;
                const pagination = payload?.pagination ?? null;
                const search = payload?.search ?? currentSearch;

                if (!renderAlumniTable(alumni, total, totalInList, pagination, search)) {
                    const apiMessage = payload?.message
                        || (search ? 'No alumni match "' + escapeHtml(search) + '".' : 'No alumni found for ' + escapeHtml(academicYear) + '.');
                    resultsBody.innerHTML = '<div class="text-gray-700">' + escapeHtml(apiMessage) + '</div>';
                    paginationPanel?.classList.add('hidden');
                    if (totalInList > 0) {
                        searchWrap?.classList.remove('hidden');
                        resultsTitle.textContent = buildResultsTitle(total, totalInList, search);
                    }
                }
            } catch (e) {
                renderDebug(appRequest, { error: e?.message || String(e) });
                resultsBody.innerHTML = '<div class="text-red-600">Unexpected error: ' + escapeHtml(e?.message || String(e)) + '</div>';
                resultsTitle.textContent = 'Alumni results (error)';
                paginationPanel?.classList.add('hidden');
                searchWrap?.classList.add('hidden');
            } finally {
                statusEl?.classList.add('hidden');
                updateSearchControls();
            }
        }

        fetchBtn?.addEventListener('click', async () => {
            const academicYear = yearSelect?.value;

            if (!academicYear) {
                resultsBody.innerHTML = '<div class="text-red-600">Please select an academic year.</div>';
                paginationPanel?.classList.add('hidden');
                searchWrap?.classList.add('hidden');
                return;
            }

            currentSearch = '';
            if (searchInput) {
                searchInput.value = '';
            }
            updateSearchControls();
            await loadAlumniPage(academicYear, 1, true, '');
        });

        paginationPrev?.addEventListener('click', async () => {
            if (!currentAcademicYear || currentPage <= 1) return;
            await loadAlumniPage(currentAcademicYear, currentPage - 1, false, currentSearch);
        });

        paginationNext?.addEventListener('click', async () => {
            if (!currentAcademicYear) return;
            await loadAlumniPage(currentAcademicYear, currentPage + 1, false, currentSearch);
        });

        searchInput?.addEventListener('input', () => {
            if (!currentAcademicYear) return;

            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(async () => {
                currentSearch = searchInput.value.trim();
                updateSearchControls();
                await loadAlumniPage(currentAcademicYear, 1, false, currentSearch);
            }, 350);
        });

        searchClearBtn?.addEventListener('click', async () => {
            if (!currentAcademicYear || !searchInput) return;
            searchInput.value = '';
            currentSearch = '';
            updateSearchControls();
            await loadAlumniPage(currentAcademicYear, 1, false, '');
        });
    });
</script>
@endsection

