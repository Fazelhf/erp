<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | خوش آمدید</title>
    <link href="[https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap](https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap)" rel="stylesheet">
    <link rel="stylesheet" href="[https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css](https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css)">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
            background: radial-gradient(circle at top right, #f1f5f9, #e2e8f0);
        }
        .auth-card {
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-3">
    <div class="w-100" style="max-width: 440px;">
        <div class="text-center mb-5 animate-fade-in">
            <div class="d-inline-flex p-3 bg-white shadow-sm rounded-4 mb-3">
                <i class="bi bi-shield-lock-fill fs-1 text-primary"></i>
            </div>
            <h2 class="fw-bold text-dark">{{ config('app.name') }}</h2>
            <p class="text-muted">مدیریت هوشمند منابع سازمانی</p>
        </div>

        <div class="card auth-card shadow-xl overflow-hidden">
            <div class="card-body p-4 p-md-5">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>