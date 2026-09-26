<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Shop · Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background: linear-gradient(145deg, #0b1a2e 0%, #1a2f42 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 2rem;
            box-shadow: 0 30px 40px -20px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.08);
            padding: 2.5rem 2rem 2.2rem;
            transition: transform 0.2s ease;
        }

        .login-container:hover {
            transform: scale(1.005);
        }

        .shop-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .shop-icon {
            font-size: 2.8rem;
            color: #0b1a2e;
            background: #eef3f9;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 18px -6px rgba(0, 20, 40, 0.2);
        }

        .shop-header h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #0b1a2e;
        }

        .shop-header .sub {
            color: #5b6f82;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .shop-header .sub i {
            font-size: 0.7rem;
            color: #2e7d5e;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1f3446;
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            background: #f4f8fe;
            border-radius: 1.2rem;
            border: 1.5px solid #e0e9f2;
            transition: border-color 0.2s, box-shadow 0.2s;
            padding: 0 1rem;
        }

        .input-wrapper:focus-within {
            border-color: #0b1a2e;
            box-shadow: 0 0 0 4px rgba(11, 26, 46, 0.08);
            background: #ffffff;
        }

        .input-wrapper i {
            color: #7a8f9f;
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        .input-wrapper input {
            width: 100%;
            border: none;
            background: transparent;
            padding: 1rem 0.2rem 1rem 0.8rem;
            font-size: 1rem;
            font-weight: 500;
            color: #0b1a2e;
            outline: none;
        }

        .input-wrapper input::placeholder {
            color: #a9bccd;
            font-weight: 400;
            font-size: 0.95rem;
        }

        .toggle-password {
            cursor: pointer;
            color: #7a8f9f;
            font-size: 1rem;
            padding: 0.5rem;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #0b1a2e;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0.5rem 0 1.8rem;
            font-size: 0.85rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #3b5263;
            font-weight: 500;
            cursor: pointer;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #0b1a2e;
            cursor: pointer;
        }

        .forgot-link {
            color: #1f4b6e;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #0b1a2e;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            background: #0b1a2e;
            border: none;
            border-radius: 2rem;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 12px 18px -10px rgba(11, 26, 46, 0.5);
            letter-spacing: 0.3px;
            margin-bottom: 1.5rem;
        }

        .btn-login i {
            font-size: 1rem;
            transition: transform 0.2s;
        }

        .btn-login:hover {
            background: #1d334b;
            box-shadow: 0 16px 22px -10px #0b1a2e;
        }

        .btn-login:hover i {
            transform: translateX(4px);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .pos-badge {
            display: inline-block;
            background: #d9e6f2;
            color: #0b1a2e;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.7rem;
            border-radius: 30px;
            letter-spacing: 0.5px;
            margin-left: 8px;
            vertical-align: middle;
            text-transform: uppercase;
        }

        /* Error alert */
        .alert-error {
            background: #fdecea;
            color: #b71c1c;
            border: 1px solid #f5c6cb;
            border-radius: 0.9rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #e6f7ed;
            color: #1b5e20;
            border: 1px solid #b2dfdb;
            border-radius: 0.9rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 480px) {
            .login-container { padding: 2rem 1.5rem; border-radius: 1.5rem; }
            .shop-icon { width: 70px; height: 70px; font-size: 2.2rem; }
            .shop-header h1 { font-size: 1.8rem; }
            .btn-login { font-size: 1rem; padding: 0.9rem 1.2rem; }
        }
    </style>
</head>
<body>

    <div class="login-container">

        <!-- Shop identity -->
        <div class="shop-header">
            <div class="shop-icon">
                <i class="fas fa-store"></i>
            </div>
            <h1>
                POS Shop
                <span class="pos-badge">terminal</span>
            </h1>
            <div class="sub">
                <i class="fas fa-circle-check"></i> secure access
            </div>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-circle-exclamation"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Success (e.g. logout message) -->
        @if (session('success'))
            <div class="alert-success">
                <i class="fas fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Login form -->
        <form action="{{ url('/login') }}" method="POST" id="loginForm">
            @csrf

            <!-- Email -->
            <div class="input-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="admin@pos.test"
                           autocomplete="username" required autofocus>
                </div>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••"
                           autocomplete="current-password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword" title="Show password"></i>
                </div>
            </div>

            <!-- Remember me -->
            <div class="form-options">
                <label class="remember">
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login">
                <span>Log in</span>
                <i class="fas fa-arrow-right-to-bracket"></i>
            </button>
        </form>

    </div>

    <!-- JS: password toggle only (no fake alerts) -->
    <script>
        (function () {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        })();
    </script>
</body>
</html>