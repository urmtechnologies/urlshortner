@extends('layout.index')
@section('title', 'Invite Team')
@section('content')<div class="page-wrapper">
        <div class="content">
            <h4 class="fw-bold mb-4">Invite Team Member</h4>
            <div class="card mb-4" style="max-width:800px">
                <div class="card-body">@include('partials.alerts')
                    <form method="POST" action="{{ route('admin.invite.store') }}">@csrf<div class="row g-3">
                            <div class="col-md-4"><label class="form-label" for="name">Name</label><input id="name"
                                    name="name" class="form-control @error('name') is-invalid @enderror" maxlength="150"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5"><label class="form-label" for="email">Email</label><input
                                    type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 d-none">
                                <label class="form-label" for="role">Role</label><select id="role" name="role"
                                    class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="member" @selected(old('role') === 'member')>Member</option>
                                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <p class="text-muted small mt-3">They will receive an email link to choose their password. The link
                            expires in 7 days.</p><button class="btn btn-primary" type="submit">Send Invitation</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invitations</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invitations as $invitation)
                                <tr>
                                    <td class="ps-4">{{ $invitation->client_name }}</td>
                                    <td>{{ $invitation->email }}</td>
                                    <td>{{ ucfirst($invitation->role) }}</td>
                                    <td>{{ $invitation->status }}</td>
                                    <td>{{ $invitation->expires_at->format('d M Y') }}</td>
                                </tr>
                            @empty<tr>
                                    <td colspan="5" class="text-center py-4">No invitations yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>@include('partials.pagination', ['items' => $invitations])
            </div>
        </div>
</div>@endsection
