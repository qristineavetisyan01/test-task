<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Leads Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="bg-slate-900 text-slate-100 w-full lg:w-64 p-6">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">LeadFlow</h1>
                <p class="text-slate-400 text-sm mt-1">Sales workspace</p>
            </div>
            <nav class="space-y-2">
                <a
                    href="{{ route('leads.index') }}"
                    class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('leads.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    Leads Dashboard
                </a>
            </nav>
        </aside>
        <div class="flex-1">
            <header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-4">
                <h2 class="text-xl sm:text-2xl font-semibold">@yield('heading', 'Leads Dashboard')</h2>
            </header>
            <main class="p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 z-[70] space-y-2"></div>

    @if (session('success'))
        <div id="initial-toast" class="hidden" data-message="{{ session('success') }}"></div>
    @endif

    @yield('scripts')
</body>
</html>
