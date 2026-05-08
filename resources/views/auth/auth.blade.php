<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Home - Sign In or Sign Up</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #3b82f6 !important;
        }

        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .auth-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(148, 163, 184, 0.08);
            border: 1px solid #e2e8f0;
            width: 100%;
            max-width: 460px;
            overflow: hidden;
        }

        .auth-header {
            padding: 30px 30px 15px 30px;
            text-align: center;
        }

        .auth-header h3 {
            color: #0f172a;
            font-weight: 700;
        }

        .nav-tabs {
            border-bottom: none;
            background: #f8fafc;
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 8px !important;
            font-weight: 600;
            color: #64748b;
            padding: 8px 24px;
            transition: all 0.2s ease-in-out;
        }

        .nav-tabs .nav-link.active {
            background-color: #3b82f6;
            color: #fff;
        }

        .auth-body {
            padding: 25px 30px 30px 30px;
        }

        .form-label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 0.95rem;
            border: 1px solid #cbd5e1;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            border-color: #3b82f6;
        }

        .btn-primary {
            background-color: #3b82f6;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .footer {
            background: #0f172a;
            color: #fff;
            padding: 24px 0;
            text-align: center;
            font-size: 0.875rem;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-home me-2"></i>Sweet Home
        </a>
    </div>
</nav>

<div class="auth-container">
    <div class="auth-card">

        <div class="auth-header">
            <h3 class="mb-1">Welcome Back!</h3>
            <p class="text-secondary mb-0">
                Get started or sign in to your dashboard
            </p>
        </div>

        <ul class="nav nav-tabs" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active"
                        id="signin-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#signin"
                        type="button"
                        role="tab">
                    Sign In
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link"
                        id="signup-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#signup"
                        type="button"
                        role="tab">
                    Sign Up
                </button>
            </li>
        </ul>

        <div class="auth-body">
            <div class="tab-content" id="authTabsContent">

                {{-- ================= LOGIN ================= --}}
                <div class="tab-pane fade show active"
                     id="signin"
                     role="tabpanel">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="signin-email" class="form-label">
                                Email Address
                            </label>

                            <input
                                id="signin-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="name@company.com"
                                required
                                autocomplete="email"
                                autofocus
                            >

                            @error('email')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="signin-password" class="form-label">
                                Password
                            </label>

                            <input
                                id="signin-password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            >

                            @error('password')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                >

                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-decoration-none text-primary small fw-medium">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100 mb-2">
                            Sign In
                        </button>
                    </form>
                </div>

                {{-- ================= REGISTER ================= --}}
                <div class="tab-pane fade"
                     id="signup"
                     role="tabpanel">

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="signup-name" class="form-label">
                                Full Name
                            </label>

                            <input
                                id="signup-name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="John Doe"
                                required
                                autocomplete="name"
                            >

                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="signup-email" class="form-label">
                                Email Address
                            </label>

                            <input
                                id="signup-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="name@company.com"
                                required
                                autocomplete="email"
                            >

                            @error('email')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="signup-number" class="form-label">
                                Number
                            </label>

                            <input
                                id="signup-number"
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="012xxxxxxx"
                                required
                            >

                            @error('phone')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="signup-password" class="form-label">
                                Password
                            </label>

                            <input
                                id="signup-password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required
                                autocomplete="new-password"
                            >

                            @error('password')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="signup-confirm-password" class="form-label">
                                Confirm Password
                            </label>

                            <input
                                id="signup-confirm-password"
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="••••••••"
                                required
                                autocomplete="new-password"
                            >
                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100 mb-2">
                            Sign Up
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="mb-0">
            &copy; 2026 Sweet Home. All rights reserved.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>