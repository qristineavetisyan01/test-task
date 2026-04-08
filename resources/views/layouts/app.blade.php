<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Leads Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">CRM Dashboard</a>
            @auth
                <div class="ms-auto d-flex gap-2">
                    <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="btn btn-outline-light btn-sm" href="{{ route('leads.index') }}">Leads</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <h1 class="h3 mb-4">@yield('heading', 'Dashboard')</h1>
            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>
</html>
