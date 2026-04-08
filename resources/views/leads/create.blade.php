@extends('layouts.app')

@section('title', 'Create Lead')
@section('heading', 'Create New Lead')

@section('content')
    <section class="card">
        <form method="POST" action="{{ route('leads.store') }}">
            @csrf
            @include('leads._form')
        </form>
    </section>
@endsection
