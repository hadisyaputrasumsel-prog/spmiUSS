<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AMIRA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-primary);
            padding: 1.5rem;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-xl);
            box-shadow: var(--card-shadow-hover);
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .login-logo {
            width: 64px;
            height: 64px;
            background: var(--brand-gradient);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">A</div>
            <h1 style="font-size: 1.5rem; font-family: 'Outfit', sans-serif; margin-bottom: 0.5rem; color: var(--text-primary);">Masuk ke AMIRA</h1>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 2rem;">Aplikasi Manajemen Audit Mutu Internal</p>
            
            @if($errors->any())
                <div style="background-color: var(--status-danger-bg); color: var(--status-danger); padding: 0.75rem; border-radius: var(--radius-md); font-size: 0.875rem; margin-bottom: 1.5rem; text-align: left;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" style="text-align: left;">
                @csrf
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="admin@uss.ac.id" style="width: 100%; padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem; font-weight: 500;">Kata Sandi</label>
                    <input type="password" name="password" required class="form-control" placeholder="••••••••" style="width: 100%; padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); background-color: var(--bg-primary); color: var(--text-primary);">
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-secondary); cursor: pointer;">
                        <input type="checkbox" name="remember" style="accent-color: var(--brand-primary);"> Ingat Saya
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 1rem;">Masuk</button>
            </form>
            
            <p style="margin-top: 2rem; font-size: 0.75rem; color: var(--text-muted);">
                &copy; {{ date('Y') }} Universitas Sumatera Selatan.
            </p>
        </div>
    </div>
    <script>
        feather.replace();
    </script>
</body>
</html>
