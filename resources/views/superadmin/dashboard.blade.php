@extends('layout.index')
@section('title', 'SuperAdmin Dashboard')
@section('content')<div class="page-wrapper">
        <div class="content">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">SuperAdmin Dashboard</h4>
                    <p class="text-muted mb-0">Client companies and their short URLs</p>
                </div><a class="btn btn-primary" href="{{ route('superadmin.invite.index') }}">Invite New Client</a>
            </div>
            @include('partials.alerts')
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Clients</h5><a class="btn btn-sm btn-light border"
                        href="{{ route('superadmin.clients.index') }}">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Client</th>
                                <th>Users</th>
                                <th>Short URLs</th>
                                <th>Total Hits</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $client->name }}</td>
                                    <td>{{ $client->users_count }}</td>
                                    <td>{{ $client->short_urls_count }}</td>
                                    <td>{{ number_format($client->short_urls_sum_hits_count ?? 0) }}</td>
                                    <td><a class="btn btn-sm btn-light border"
                                            href="{{ route('superadmin.clients.show', $client) }}">View</a></td>
                            </tr>@empty<tr>
                                    <td colspan="5" class="text-center text-muted py-4">No clients yet. Invite an Admin
                                        to get started.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @include('partials.recent-urls')
        </div>
</div>@endsection
