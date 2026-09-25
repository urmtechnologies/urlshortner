@extends('layout.index')

@section('title', 'New Invite')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            {{-- Page heading --}}
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1 fw-bold">Invite New Client</h4>
                    <p class="text-muted mb-0">
                        Invite an Admin to set up a new Client account.
                    </p>
                </div>

                <a href="{{ route('superadmin.clients.index') }}" class="btn btn-light border">
                    <i class="ti ti-arrow-left me-1"></i> Back to Clients
                </a>
            </div>

            {{-- Invite form --}}
            <div class="row">
                <div class="col-xl-8 col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Client Details</h5>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    <i class="ti ti-circle-check me-1"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form id="inviteForm" action="{{ route('superadmin.invite.store') }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">
                                            Client Name <span class="text-danger">*</span>
                                        </label>

                                        <input id="name" name="name" type="text" value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="e.g. Sembark Tech" maxlength="150" autocomplete="organization"
                                            required>

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            Admin Email <span class="text-danger">*</span>
                                        </label>

                                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="e.g. admin@client.com" maxlength="255" autocomplete="email"
                                            required>

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <p class="text-muted fs-13 mt-3 mb-0">
                                    The Admin will receive an email link to set a password
                                    and activate this Client account.
                                </p>

                                <div class="d-flex align-items-center gap-2 mt-4">
                                    <button id="inviteSubmit" type="submit" class="btn btn-primary">
                                        <span id="inviteSpinner" class="spinner-border spinner-border-sm me-1 d-none"
                                            aria-hidden="true"></span>
                                        <i id="inviteIcon" class="ti ti-send me-1"></i>
                                        <span id="inviteButtonText">Send Invitation</span>
                                    </button>

                                    <a href="{{ route('superadmin.clients.index') }}" class="btn btn-light border">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Invitation status list --}}
            <div class="card mt-4">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div>
                        <h5 class="card-title mb-1">Sent Invitations</h5>
                        <small class="text-muted">
                            See whether the Client Admin has accepted the invitation.
                        </small>
                    </div>

                    <span class="badge bg-light text-dark border">
                        {{ $invitations->total() }} Total
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Client Name</th>
                                    <th>Admin Email</th>
                                    <th>Invited By</th>
                                    <th>Sent On</th>
                                    <th>Expires On</th>
                                    <th class="text-end pe-3">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($invitations as $invitation)
                                    <tr>
                                        <td class="ps-3 fw-semibold">
                                            {{ $invitation->client_name }}
                                        </td>

                                        <td>{{ $invitation->email }}</td>

                                        <td>
                                            {{ $invitation->inviter?->name ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $invitation->created_at->format('d M Y') }}
                                        </td>

                                        <td>
                                            {{ $invitation->expires_at->format('d M Y') }}
                                        </td>

                                        <td class="text-end pe-3">
                                            @if ($invitation->status === 'Accepted')
                                                <span class="badge bg-success">
                                                    <i class="ti ti-check me-1"></i>
                                                    Accepted
                                                </span>
                                            @elseif ($invitation->status === 'Expired')
                                                <span class="badge bg-danger">
                                                    Expired
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No invitations sent yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($invitations->hasPages())
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <small class="text-muted">
                            Showing {{ $invitations->firstItem() }}
                            to {{ $invitations->lastItem() }}
                            of {{ $invitations->total() }}
                        </small>

                        <div class="d-flex gap-2">
                            @if ($invitations->onFirstPage())
                                <button class="btn btn-sm btn-light border" disabled>
                                    Previous
                                </button>
                            @else
                                <a href="{{ $invitations->previousPageUrl() }}" class="btn btn-sm btn-light border">
                                    Previous
                                </a>
                            @endif

                            @if ($invitations->hasMorePages())
                                <a href="{{ $invitations->nextPageUrl() }}" class="btn btn-sm btn-light border">
                                    Next
                                </a>
                            @else
                                <button class="btn btn-sm btn-light border" disabled>
                                    Next
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('inviteForm');
            const button = document.getElementById('inviteSubmit');
            const spinner = document.getElementById('inviteSpinner');
            const icon = document.getElementById('inviteIcon');
            const buttonText = document.getElementById('inviteButtonText');

            form.addEventListener('submit', function(event) {
                if (button.disabled) {
                    event.preventDefault();
                    return;
                }

                if (!form.checkValidity()) {
                    return;
                }

                button.disabled = true;
                spinner.classList.remove('d-none');
                icon.classList.add('d-none');
                buttonText.textContent = 'Sending...';
            });
        });
    </script>
@endsection
