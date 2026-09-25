@extends('layout.index')
@section('title', 'Clients')
@section('content')<div class="page-wrapper">
        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Clients</h4><a class="btn btn-primary"
                    href="{{ route('superadmin.invite.index') }}">Invite Client Admin</a>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Client</th>
                                <th>Admins</th>
                                <th>Users</th>
                                <th>Short URLs</th>
                                <th>Hits</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $client->name }}</td>
                                    <td>{{ $client->users->pluck('email')->join(', ') ?: '—' }}</td>
                                    <td>{{ $client->users_count }}</td>
                                    <td>{{ $client->short_urls_count }}</td>
                                    <td>{{ number_format($client->short_urls_sum_hits_count ?? 0) }}</td>
                                    <td><a class="btn btn-sm btn-light border"
                                            href="{{ route('superadmin.clients.show', $client) }}">View Details</a></td>
                                </tr>
                            @empty<tr>
                                    <td colspan="6" class="text-center text-muted py-4">No clients yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>@include('partials.pagination', ['items' => $clients])
            </div>
        </div>
</div>@endsection
