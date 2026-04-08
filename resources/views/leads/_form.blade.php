@php
    $isEdit = isset($lead);
@endphp

<div class="form-grid">
    <div>
        <label for="name">Name *</label>
        <input id="name" type="text" name="name" value="{{ old('name', $lead->name ?? '') }}" required>
        @error('name') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email">Email *</label>
        <input id="email" type="email" name="email" value="{{ old('email', $lead->email ?? '') }}" required>
        @error('email') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="phone">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $lead->phone ?? '') }}">
        @error('phone') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="company">Company</label>
        <input id="company" type="text" name="company" value="{{ old('company', $lead->company ?? '') }}">
        @error('company') <p class="error-text">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="status">Status *</label>
        <select name="status" id="status" required>
            @foreach (\App\Models\Lead::STATUSES as $status)
                <option value="{{ $status }}" @selected(old('status', $lead->status ?? 'new') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="error-text">{{ $message }}</p> @enderror
    </div>
</div>

<div class="form-notes">
    <label for="notes">Notes</label>
    <textarea name="notes" id="notes" rows="5">{{ old('notes', $lead->notes ?? '') }}</textarea>
    @error('notes') <p class="error-text">{{ $message }}</p> @enderror
</div>

<div class="form-actions">
    <a href="{{ route('leads.index') }}" class="button-muted">Cancel</a>
    <button type="submit">{{ $isEdit ? 'Update Lead' : 'Create Lead' }}</button>
</div>
