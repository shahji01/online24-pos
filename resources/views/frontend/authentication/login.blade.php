@php
    $siteName = readConfig('site_name');
    $siteLogo = assetImage(readConfig('site_logo'));
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ $siteName }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ $siteLogo }}" type="image/png">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    <!-- Back to Top -->
    <link rel="stylesheet" href="{{ asset('assets/css/back-top/backToTop.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc, #eef2f7);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            animation: fadeIn 0.7s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card img.logo {
            display: block;
            margin: 0 auto 1rem;
            width: 150px;
        }

        .auth-card h3 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-card p.description {
            text-align: center;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 38px;
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            border-radius: 8px;
            background-color: #2563eb;
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .text-muted small a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .text-muted small a:hover {
            text-decoration: underline;
        }

        .demo-info {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <x-simple-alert />

    <div class="auth-card">
        <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <img src="{{ $siteLogo }}" alt="logo" class="logo">
            <h3>Welcome Back</h3>
            <p class="description">Sign in to access your account</p>

            <div class="mb-3 position-relative">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control"
                    placeholder="Enter email" required autocomplete="off">
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Enter password" required autocomplete="off">
                <div class="invalid-feedback">Please enter your password.</div>
                <div class="toggle-password" id="toggleIcon">
                    <svg class="eye-icon" width="20" height="20" fill="none" stroke="#6c757d" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 10s3-6 9-6 9 6 9 6-3 6-9 6-9-6-9-6zm9 2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                    </svg>
                    <svg class="eye-off d-none" width="20" height="20" fill="none" stroke="#6c757d"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94L1.06 1.06M10.58 10.58a3 3 0 0 1-4.24-4.24" />
                        <path d="M9 3C4 3 1 9 1 9s3 6 8 6a7.67 7.67 0 0 0 4.73-1.64" />
                    </svg>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
                <a href="{{ route('forget.password') }}" class="text-decoration-none text-primary fw-semibold">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary">Sign In</button>

            <div class="text-center mt-3 text-muted">
                <small>Don’t have an account?
                    <a href="{{ route('signup') }}">Sign up</a>
                </small>
            </div>

            <div class="demo-info">
                <strong>Demo Login:</strong><br>
                <b>User:</b> demo@qtecsolution.net<br>
                <b>Password:</b> 87654321
            </div>
        </form>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Password toggle
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        const eye = toggleIcon.querySelector('.eye-icon');
        const eyeOff = toggleIcon.querySelector('.eye-off');

        toggleIcon.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eye.classList.toggle('d-none', !isPassword);
            eyeOff.classList.toggle('d-none', isPassword);
        });

        // Bootstrap validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>
