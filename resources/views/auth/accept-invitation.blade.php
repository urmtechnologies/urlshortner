<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation | Sembark</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="card shadow-sm w-100" style="max-width: 480px;">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Sembark" class="img-fluid mb-4"
                        style="max-width: 150px;">

                    <h4 class="fw-bold mb-2">Accept your invitation</h4>
                    <p class="text-muted mb-0">
                        Set your password to activate your {{ ucfirst($invitation->role) }} account.
                    </p>
                </div>

                @if ($errors->has('invitation'))
                    <div class="alert alert-danger">
                        {{ $errors->first('invitation') }}
                    </div>
                @endif

                <form action="{{ route('invitations.accept.store', $token) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name"
                            maxlength="150" autocomplete="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input id="password" name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter new password" autocomplete="new-password" minlength="8" required>
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="password"
                                aria-label="Show password">
                                <i class="ti ti-eye-off"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="form-control" placeholder="Enter password again" autocomplete="new-password"
                                minlength="8" required>
                            <button class="btn btn-outline-secondary" type="button"
                                data-toggle-password="password_confirmation" aria-label="Show password">
                                <i class="ti ti-eye-off"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Activate Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = document.getElementById(button.dataset.togglePassword);
                const icon = button.querySelector('i');
                const isVisible = input.type === 'text';

                input.type = isVisible ? 'password' : 'text';
                icon.classList.toggle('ti-eye', !isVisible);
                icon.classList.toggle('ti-eye-off', isVisible);
                button.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            });
        });
    </script>
</body>

</html>
