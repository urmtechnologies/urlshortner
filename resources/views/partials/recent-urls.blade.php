<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h5 class="mb-0">Generated Short URLs</h5>
        <a class="btn btn-sm btn-light border" href="{{ route(auth()->user()->role . '.short-urls.index') }}">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Short URL</th>
                    <th>Long URL</th>
                    @if (auth()->user()->role === 'superadmin')
                        <th>Client</th>
                        @endif @if (auth()->user()->role !== 'member')
                            <th>Created By</th>
                        @endif
                        <th>Hits</th>
                        <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($urls as $url)
                    <tr>
                        <td class="ps-4"><a href="{{ route('short.resolve', $url->code) }}" target="_blank"
                                rel="noopener noreferrer">{{ route('short.resolve', $url->code) }}</a></td>
                        <td style="max-width:280px"><span class="d-block text-truncate"
                                title="{{ $url->original_url }}">{{ $url->original_url }}</span></td>
                        @if (auth()->user()->role === 'superadmin')
                            <td>{{ $url->client?->name ?? '—' }}</td>
                        @endif
                        @if (auth()->user()->role !== 'member')
                            <td>{{ $url->user?->name ?? '—' }}</td>
                        @endif
                        <td>{{ number_format($url->hits_count) }}</td>
                        <td>{{ $url->created_at->format('d M Y') }}</td>
                    </tr>
                @empty<tr>
                        <td colspan="6" class="text-center text-muted py-4">No short URLs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
