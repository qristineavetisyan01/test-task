@extends('layouts.app')

@section('title', 'Edit Lead')
@section('heading', 'Edit Lead')

@section('content')
    <section class="card">
        <form method="POST" action="{{ route('leads.update', $lead) }}">
            @csrf
            @method('PUT')
            @include('leads._form', ['lead' => $lead])
        </form>
    </section>
@endsection
