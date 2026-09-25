<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign In | Sembark</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sign In | Sembark">

    <link rel="shortcut icon" href="{{ asset('assets') }}/img/favicon.ico">
    <link rel="apple-touch-icon" href="{{ asset('assets') }}/img/favicon.ico">

    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/tabler-icons/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/simplebar/simplebar.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" id="app-style">

    <style>
        .password-toggle-btn {
            border: 0;
            cursor: pointer;
        }

        .password-toggle-btn:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: -2px;
        }

        .field-error {
            display: block;
            min-height: 18px;
            margin-top: 5px;
            font-size: 12px;
        }

        .login-alert {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="container-fluid position-relative z-1">
            <div class="w-100 overflow-hidden position-relative d-block min-vh-100 bg-white lock-screen-cover">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="row justify-content-center align-items-center min-vh-100 py-4">
                            <div class="col-md-8 mx-auto">

                                <form id="loginForm" action="{{ route('login.submit') }}" method="POST"
                                    class="d-flex justify-content-center align-items-center" novalidate>
                                    @csrf

                                    <div class="d-flex flex-column justify-content-lg-center flex-fill">
                                        <div class="card border-1 p-lg-3 shadow-md rounded-3 m-0">
                                            <div class="card-body">

                                                <div class="mb-4 d-flex justify-content-center">
                                                    <a href="/">
                                                        <img src="{{ asset('assets') }}/img/logo.png"
                                                            class="img-fluid logo" alt="Sembark Logo">
                                                    </a>
                                                </div>

                                                <div class="mb-3">
                                                    <h5 class="mb-1 fw-bold">Hi, Welcome Back</h5>
                                                </div>

                                                <div id="loginMessage" class="alert alert-danger login-alert d-none"
                                                    role="alert"></div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">
                                                        Email
                                                        <span class="text-danger ms-1">*</span>
                                                    </label>

                                                    <div class="input-group input-group-flat">
                                                        <input id="email" name="email" type="email"
                                                            value="{{ old('email') }}"
                                                            class="form-control border-end-0" autocomplete="username"
                                                            required autofocus>

                                                        <span class="input-group-text bg-white">
                                                            <i class="ti ti-mail fs-14 text-dark"></i>
                                                        </span>
                                                    </div>

                                                    <small id="emailError" class="field-error text-danger"
                                                        role="alert"></small>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="password" class="form-label">
                                                        Password
                                                        <span class="text-danger ms-1">*</span>
                                                    </label>

                                                    <div class="input-group input-group-flat pass-group">
                                                        <input id="password" name="password" type="password"
                                                            class="form-control pass-input"
                                                            autocomplete="current-password" required>

                                                        <button id="passwordToggle" type="button"
                                                            class="input-group-text password-toggle-btn"
                                                            aria-label="Show password" aria-pressed="false"
                                                            aria-controls="password">
                                                            <i id="passwordIcon" class="ti ti-eye-off"
                                                                aria-hidden="true"></i>
                                                        </button>
                                                    </div>

                                                    <small id="passwordError" class="field-error text-danger"
                                                        role="alert"></small>
                                                </div>

                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="form-check form-check-md mb-0">
                                                        <input id="remember_me" name="remember" class="form-check-input"
                                                            type="checkbox" value="1" @checked(old('remember'))>

                                                        <label for="remember_me"
                                                            class="form-check-label mt-0 text-body">
                                                            Remember Me
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="mb-0">
                                                    <button id="loginSubmit" type="submit"
                                                        class="btn bg-primary text-white w-100">
                                                        <span id="loginSpinner"
                                                            class="spinner-border spinner-border-sm me-2 d-none"
                                                            aria-hidden="true"></span>
                                                        <span id="loginButtonText">Sign In</span>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets') }}/js/jquery.min.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets') }}/js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordIcon = document.getElementById('passwordIcon');

            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const loginMessage = document.getElementById('loginMessage');

            const submitButton = document.getElementById('loginSubmit');
            const spinner = document.getElementById('loginSpinner');
            const buttonText = document.getElementById('loginButtonText');

            let submitting = false;

            function setFieldError(input, errorElement, message) {
                errorElement.textContent = message;
                input.classList.toggle('is-invalid', Boolean(message));
                input.setAttribute('aria-invalid', message ? 'true' : 'false');
            }

            function validateEmail() {
                const value = email.value.trim();

                if (!value) {
                    setFieldError(email, emailError, 'Email is required.');
                    return false;
                }

                if (!email.validity.valid) {
                    setFieldError(email, emailError, 'Enter a valid email address.');
                    return false;
                }

                setFieldError(email, emailError, '');
                return true;
            }

            function validatePassword() {
                if (!password.value) {
                    setFieldError(password, passwordError, 'Password is required.');
                    return false;
                }

                setFieldError(password, passwordError, '');
                return true;
            }

            function showMessage(message) {
                loginMessage.textContent = message;
                loginMessage.classList.remove('d-none');
            }

            function hideMessage() {
                loginMessage.textContent = '';
                loginMessage.classList.add('d-none');
            }

            function setLoading(loading) {
                submitButton.disabled = loading;
                spinner.classList.toggle('d-none', !loading);
                buttonText.textContent = loading ? 'Signing In...' : 'Sign In';
            }

            // Password show/hide
            passwordToggle.addEventListener('click', function() {
                const showPassword = password.type === 'password';

                password.type = showPassword ? 'text' : 'password';

                passwordIcon.classList.toggle('ti-eye', showPassword);
                passwordIcon.classList.toggle('ti-eye-off', !showPassword);

                passwordToggle.setAttribute(
                    'aria-label',
                    showPassword ? 'Hide password' : 'Show password'
                );

                passwordToggle.setAttribute('aria-pressed', String(showPassword));
            });

            // Live validation
            email.addEventListener('input', function() {
                validateEmail();
                hideMessage();
            });

            password.addEventListener('input', function() {
                validatePassword();
                hideMessage();
            });

            // AJAX login
            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                if (submitting) return;

                hideMessage();

                const validEmail = validateEmail();
                const validPassword = validatePassword();

                if (!validEmail || !validPassword) return;

                submitting = true;
                setLoading(true);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    let data;

                    try {
                        data = await response.json();
                    } catch (error) {
                        throw new Error('Unexpected server response.');
                    }

                    if (!response.ok) {
                        setFieldError(
                            email,
                            emailError,
                            data.errors?.email?.[0] || ''
                        );

                        setFieldError(
                            password,
                            passwordError,
                            data.errors?.password?.[0] || ''
                        );

                        if (response.status === 419) {
                            showMessage('Session expired. Refresh the page and try again.');
                        } else if (!data.errors) {
                            showMessage(data.message || 'Unable to sign in. Please try again.');
                        }

                        return;
                    }

                    if (!data.redirect) {
                        throw new Error('Login succeeded, but redirect URL was not returned.');
                    }

                    window.location.assign(data.redirect);
                } catch (error) {
                    showMessage(error.message || 'Connection error. Please try again.');
                } finally {
                    submitting = false;
                    setLoading(false);
                }
            });
        });
    </script>
</body>

</html>
