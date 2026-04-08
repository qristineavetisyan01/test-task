<div class="table-responsive">
    <table class="leads-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($leads as $lead)
                <tr id="lead-row-{{ $lead->id }}">
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->email }}</td>
                    <td>{{ $lead->phone ?: '-' }}</td>
                    <td>{{ $lead->company ?: '-' }}</td>
                    <td><x-status-badge :status="$lead->status" /></td>
                    <td class="actions-cell">
                        <a href="{{ route('leads.edit', $lead) }}" class="button-muted">Edit</a>
                        <button
                            type="button"
                            class="button-danger js-delete-lead"
                            data-url="{{ route('leads.destroy', $lead) }}"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">No leads found for the selected criteria.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $leads->links() }}
</div>
