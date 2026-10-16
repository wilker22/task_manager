<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
        }
        .auth-card { max-width: 420px; width: 100%; border: 0; border-radius: 1rem; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <div class="card auth-card shadow-lg">
        <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-4">
                <i class="bi bi-check2-square text-primary" style="font-size: 2.5rem;"></i>
                <h1 class="h4 fw-semibold mt-2 mb-1">{{ config('app.name') }}</h1>
                <p class="text-muted small mb-0">@yield('subtitle')</p>
            </div>

            @yield('content')
        </div>
    </div>
</body>
</html>
