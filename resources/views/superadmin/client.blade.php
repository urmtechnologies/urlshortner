@extends('layout.index')
@section('title', $client->name)
@section('content')<div class="page-wrapper">
        <div class="content"><a class="btn btn-sm btn-light border mb-3" href="{{ route('superadmin.clients.index') }}">Back to
                Clients</a>
            <h4 class="fw-bold mb-4">{{ $client->name }}</h4>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Admins &amp; Members</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>URLs</th>
                                <th>Hits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>{{ $user->short_urls_count }}</td>
                                    <td>{{ number_format($user->short_urls_sum_hits_count ?? 0) }}</td>
                                </tr>
                            @empty<tr>
                                    <td colspan="5" class="text-center py-4">No users yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>@include('partials.pagination', ['items' => $users])
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Short URLs</h5><a
                        href="{{ route('superadmin.short-urls.index', ['period' => 'all', 'client_id' => $client->id]) }}"
                        class="btn btn-sm btn-light border">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Short URL</th>
                                <th>Long URL</th>
                                <th>Created By</th>
                                <th>Hits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($urls as $url)
                                <tr>
                                    <td class="ps-4"><a
                                            href="{{ route('short.resolve', $url->code) }}">{{ route('short.resolve', $url->code) }}</a>
                                    </td>
                                    <td style="max-width:320px"><span class="d-block text-truncate"
                                            title="{{ $url->original_url }}">{{ $url->original_url }}</span></td>
                                    <td>{{ $url->user?->name ?? '—' }}</td>
                                    <td>{{ $url->hits_count }}</td>
                                </tr>
                            @empty<tr>
                                    <td colspan="4" class="text-center py-4">No URLs yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>@endsection
