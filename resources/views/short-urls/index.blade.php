@extends('layout.index')
@section('title', 'Short URLs')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Generated Short URLs</h4>
                    <p class="text-muted mb-0">
                        @if (auth()->user()->role === 'member')
                            Your links
                        @elseif(auth()->user()->role === 'admin')
                            Your company links
                        @else
                            All clients' links
                        @endif
                    </p>
                </div>
                @if (auth()->user()->role !== 'superadmin')
                    <a class="btn btn-primary" href="{{ route(auth()->user()->role . '.short-urls.create') }}"><i
                            class="ti ti-plus me-1"></i> Generate</a>
                @endif
            </div>
            @include('partials.alerts')
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <h5 class="mb-0">Short URLs</h5>
                    <form class="d-flex gap-2" method="GET"
                        action="{{ route(auth()->user()->role . '.short-urls.index') }}">
                        @if (auth()->user()->role === 'superadmin' && request()->filled('client_id'))
                            <input type="hidden" name="client_id" value="{{ request('client_id') }}">
                        @endif
                        <select name="period" class="form-select" aria-label="Date interval" onchange="this.form.submit()">
                            <option value="month" @selected($period === 'month')>This Month</option>
                            <option value="last_month" @selected($period === 'last_month')>Last Month</option>
                            <option value="last_week" @selected($period === 'last_week')>Last Week</option>
                            <option value="today" @selected($period === 'today')>Today</option>
                            <option value="all" @selected($period === 'all')>All Time</option>
                        </select>
                        <a class="btn btn-outline-primary"
                            href="{{ route(auth()->user()->role . '.short-urls.download', ['period' => $period, 'client_id' => auth()->user()->role === 'superadmin' ? request('client_id') : null]) }}">Download
                            CSV</a>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Short URL</th>
                                <th>Long URL</th>
                                @if (auth()->user()->role === 'superadmin')
                                    <th>Client</th>
                                @endif
                                @if (auth()->user()->role !== 'member')
                                    <th>Created By</th>
                                @endif
                                <th>Hits</th>
                                <th>Created On</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($urls as $url)
                                <tr>
                                    <td class="ps-4"><a href="{{ route('short.resolve', $url->code) }}" target="_blank"
                                            rel="noopener noreferrer">{{ route('short.resolve', $url->code) }}</a></td>
                                    <td style="max-width:280px"><a class="d-block text-truncate"
                                            href="{{ $url->original_url }}" target="_blank" rel="noopener noreferrer"
                                            title="{{ $url->original_url }}">{{ $url->original_url }}</a></td>
                                    @if (auth()->user()->role === 'superadmin')
                                        <td>{{ $url->client?->name ?? '—' }}</td>
                                    @endif
                                    @if (auth()->user()->role !== 'member')
                                        <td>{{ $url->user?->name ?? '—' }}</td>
                                    @endif
                                    <td>{{ number_format($period === 'all' ? $url->hits_count : $url->period_hits) }}</td>
                                    <td>{{ $url->created_at->format('d M Y') }}</td>
                                    <td><button class="btn btn-sm btn-light border copy-link" type="button"
                                            data-url="{{ route('short.resolve', $url->code) }}">Copy</button></td>
                                </tr>
                            @empty <tr>
                                    <td class="text-center text-muted py-4" colspan="7">No short URLs for this interval.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('partials.pagination', ['items' => $urls])
            </div>
        </div>
    </div>
@endsection
