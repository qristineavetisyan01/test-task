@extends('layouts.app')

@section('title', 'Leads Dashboard')
@section('heading', 'Leads Management Dashboard')

@section('content')
    <section class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
            <form id="lead-filters-form" method="GET" action="{{ route('leads.index') }}" class="flex flex-col sm:flex-row gap-2 sm:items-center w-full">
                <input
                    id="search-input"
                    type="text"
                    name="search"
                    value="{{ $filters['search'] }}"
                    placeholder="Search by name or email"
                    class="w-full sm:max-w-sm rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition"
                >
                <select
                    name="status"
                    id="status-select"
                    class="w-full sm:w-52 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition"
                >
                    <option value="">All statuses</option>
                    @foreach (\App\Models\Lead::STATUSES as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('leads.index') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 transition">Reset</a>
            </form>

            <button
                id="open-create-modal"
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition"
            >
                Add Lead
            </button>
        </div>

        <div id="table-loading" class="hidden mb-3 text-sm text-blue-600">Loading leads...</div>
        <div id="leads-table-wrapper" class="transition-opacity duration-200">
            @include('leads._table', ['leads' => $leads])
        </div>
    </section>

    <x-modal id="lead-form-modal" title="Lead Form">
        <form id="lead-form" class="space-y-4" data-create-url="{{ route('leads.store') }}" data-action="{{ route('leads.store') }}">
            <input type="hidden" id="lead-form-method" value="POST">
            <input type="hidden" id="lead-id">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="lead-name" class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
                    <input id="lead-name" name="name" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" required>
                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="name"></p>
                </div>
                <div>
                    <label for="lead-email" class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                    <input id="lead-email" name="email" type="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" required>
                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="email"></p>
                </div>
                <div>
                    <label for="lead-phone" class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                    <input id="lead-phone" name="phone" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="phone"></p>
                </div>
                <div>
                    <label for="lead-company" class="block text-sm font-medium text-slate-700 mb-1">Company</label>
                    <input id="lead-company" name="company" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition">
                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="company"></p>
                </div>
                <div>
                    <label for="lead-status" class="block text-sm font-medium text-slate-700 mb-1">Status *</label>
                    <select id="lead-status" name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition" required>
                        @foreach (\App\Models\Lead::STATUSES as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-red-600 hidden" data-error-for="status"></p>
                </div>
            </div>

            <div>
                <label for="lead-notes" class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                <textarea id="lead-notes" name="notes" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition"></textarea>
                <p class="mt-1 text-xs text-red-600 hidden" data-error-for="notes"></p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition" data-close-modal="lead-form-modal">Cancel</button>
                <button id="lead-form-submit" type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">Save Lead</button>
            </div>
        </form>
    </x-modal>

    <x-modal id="delete-confirm-modal" title="Delete Lead">
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.516 11.59c.75 1.334-.213 2.99-1.742 2.99H3.483c-1.53 0-2.492-1.656-1.743-2.99l6.517-11.59zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v4a1 1 0 102 0V7a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-800">Are you sure you want to delete <span id="delete-lead-name" class="font-semibold"></span>?</p>
                    <p class="text-xs text-slate-500 mt-1">This action cannot be undone.</p>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition" data-close-modal="delete-confirm-modal">Cancel</button>
                <button id="confirm-delete-btn" type="button" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">Delete</button>
            </div>
        </div>
    </x-modal>
@endsection

@section('scripts')
    <script src="{{ asset('js/leads.js') }}" defer></script>
@endsection
