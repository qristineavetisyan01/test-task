@extends('layouts.app')

@section('title', 'Leads Dashboard')
@section('heading', 'Leads Management Dashboard')

@section('content')
    <section class="card">
        <div class="filters-row">
            <form id="lead-filters-form" method="GET" action="{{ route('leads.index') }}" class="filters-form">
                <input
                    id="search-input"
                    type="text"
                    name="search"
                    value="{{ $filters['search'] }}"
                    placeholder="Search by name or email"
                >
                <select name="status" id="status-select">
                    <option value="">All statuses</option>
                    @foreach (\App\Models\Lead::STATUSES as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Apply</button>
                <a href="{{ route('leads.index') }}" class="button-muted">Reset</a>
            </form>
        </div>

        <div id="leads-table-wrapper">
            @include('leads._table', ['leads' => $leads])
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/leads.js') }}" defer></script>
@endsection
