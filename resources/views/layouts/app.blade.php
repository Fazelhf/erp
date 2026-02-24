<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" href="{{ asset('images/op.jpg') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .main-content {
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modern-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        #global-loader {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            z-index: 9999;
        }
    </style>
</head>
<body class="min-vh-100 d-flex flex-column">

    <!-- Global Loader -->
    <div id="global-loader" class="position-fixed top-0 start-0 w-100 h-100 d-none justify-content-center align-items-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>

    <!-- Navbar -->
    @include('layouts.navbar')

    <!-- Main Content -->
    <main class="flex-grow-1 main-content py-4">
        <div class="container-fluid px-4">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center animate-fade-in mb-4" role="alert">
                    <i class="bi bi-check2-circle fs-4 me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll("form").forEach(f => {
                f.addEventListener("submit", () => {
                    document.getElementById("global-loader").classList.replace("d-none", "d-flex");
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>