@extends('layout.index')
@section('title','Admin Dashboard')
@section('content')<div class="page-wrapper"><div class="content">
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4"><div><h4 class="fw-bold mb-1">Admin Dashboard</h4><p class="text-muted mb-0">{{ auth()->user()->client->name }} · Team and short URLs</p></div><div class="d-flex gap-2"><a class="btn btn-primary" href="{{ route('admin.short-urls.create') }}">Generate URL</a><a class="btn btn-light border" href="{{ route('admin.invite.index') }}">Invite Team</a></div></div>
@include('partials.alerts')
<div class="card mb-4"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Team Members</h5><a class="btn btn-sm btn-light border" href="{{ route('admin.members.index') }}">View All</a></div><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th class="ps-4">Name</th><th>Email</th><th>Role</th><th>Short URLs</th><th>Total Hits</th></tr></thead><tbody>
@forelse($users as $user)<tr><td class="ps-4">{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ ucfirst($user->role) }}</td><td>{{ $user->short_urls_count }}</td><td>{{ number_format($user->short_urls_sum_hits_count ?? 0) }}</td></tr>@empty<tr><td colspan="5" class="text-center py-4">No team members yet.</td></tr>@endforelse
</tbody></table></div></div>
@include('partials.recent-urls')
</div></div>@endsection
