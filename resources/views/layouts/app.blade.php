<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Leads Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div>
                <h1>LeadFlow</h1>
                <p>Sales workspace</p>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('leads.index') }}" class="{{ request()->routeIs('leads.index') ? 'active' : '' }}">Leads</a>
                <a href="{{ route('leads.create') }}" class="{{ request()->routeIs('leads.create') ? 'active' : '' }}">Create Lead</a>
            </nav>
        </aside>
        <div class="main-content">
            <header class="topbar">
                <h2>@yield('heading', 'Leads Dashboard')</h2>
            </header>
            <main>
                @if (session('success'))
                    <div class="flash flash-success">{{ session('success') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @yield('scripts')
</body>
</html>
