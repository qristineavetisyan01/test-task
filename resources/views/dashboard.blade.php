@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'CRM Dashboard')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Overview</h5>
            <p class="card-text mb-0">Total Leads: <strong>{{ $totalLeads }}</strong></p>
        </div>
    </div>
@endsection
