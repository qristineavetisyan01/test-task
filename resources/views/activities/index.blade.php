@extends('layouts.app')

@section('title', 'Activities')
@section('heading', 'Lead Activities')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Add Activity</h5>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('activities.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Lead</label>
                            <select name="lead_id" class="form-select" required>
                                <option value="">Select lead</option>
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}" @selected(old('lead_id') == $lead->id)>
                                        {{ $lead->name }} ({{ $lead->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('lead_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="call" @selected(old('type') === 'call')>call</option>
                                <option value="email" @selected(old('type') === 'email')>email</option>
                                <option value="meeting" @selected(old('type') === 'meeting')>meeting</option>
                                <option value="note" @selected(old('type') === 'note')>note</option>
                            </select>
                            @error('type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-primary w-100" type="submit">Save Activity</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recent Activities</h5>

                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->lead?->name }}</td>
                                        <td>{{ $activity->user?->name }}</td>
                                        <td><span class="badge text-bg-secondary">{{ $activity->type }}</span></td>
                                        <td>{{ $activity->description }}</td>
                                        <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('activities.destroy', $activity) }}" onsubmit="return confirm('Delete this activity?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No activities yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
