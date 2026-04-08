@props(['leads'])

<div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="min-w-full bg-white">
        <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Phone</th>
                <th class="px-4 py-3 text-left">Company</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            @forelse ($leads as $lead)
                <tr id="lead-row-{{ $lead->id }}" class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $lead->name }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $lead->email }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $lead->phone ?: '-' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $lead->company ?: '-' }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$lead->status" /></td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap justify-end gap-2">
                            <button
                                type="button"
                                class="js-edit-lead inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 transition"
                                data-id="{{ $lead->id }}"
                                data-name="{{ $lead->name }}"
                                data-email="{{ $lead->email }}"
                                data-phone="{{ $lead->phone }}"
                                data-company="{{ $lead->company }}"
                                data-status="{{ $lead->status }}"
                                data-notes="{{ $lead->notes }}"
                                data-update-url="{{ route('leads.update', $lead) }}"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="js-delete-lead inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-100 transition"
                                data-url="{{ route('leads.destroy', $lead) }}"
                                data-name="{{ $lead->name }}"
                            >
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-14 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 3a1 1 0 011 1v1.07A7.002 7.002 0 0117 12a1 1 0 11-2 0 5 5 0 10-5 5 1 1 0 110 2A7 7 0 119 5.07V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-slate-900">No leads found</h3>
                            <p class="mt-1 text-sm text-slate-500">Try adjusting your search or status filter.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<x-pagination :paginator="$leads" />
