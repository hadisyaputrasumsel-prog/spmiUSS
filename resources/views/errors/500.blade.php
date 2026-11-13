<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan Server | AMIRA USS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --brand-primary: #1e40af;
            --border-color: #e2e8f0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
            box-sizing: border-box;
        }
        .error-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2.5rem;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .error-code {
            font-size: 4rem;
            font-weight: 800;
            color: #ef4444;
            line-height: 1;
            margin: 0 0 1rem 0;
        }
        .error-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.75rem 0;
        }
        .error-desc {
            font-size: 0.95rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 0 0 2rem 0;
        }
        .btn {
            display: inline-block;
            background: var(--brand-primary);
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: opacity 0.2s;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .notice-box {
            margin-top: 1.5rem;
            padding: 0.85rem;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            font-size: 0.8rem;
            color: #92400e;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">500</div>
        <h1 class="error-title">Terjadi Gangguan pada Server</h1>
        <p class="error-desc">
            Sistem sedang mengalami kendala teknis internal. Apabila Anda administrator sistem, pastikan konfigurasi dan migrasi basis data terbaru telah dijalankan dengan lengkap.
        </p>

        <a href="/" class="btn">Kembali ke Beranda AMIRA</a>

        <div class="notice-box">
            <strong>Catatan Administrator:</strong> Jika error terjadi setelah pembaruan kode, jalankan perintah migrasi pada terminal server: <code>php artisan migrate</code>.
        </div>
    </div>
</body>
</html>
