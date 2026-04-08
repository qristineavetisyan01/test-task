@props([
    'id',
    'title' => 'Modal',
])

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden items-center justify-center"
    aria-hidden="true"
>
    <div class="absolute inset-0 bg-slate-900/50 transition-opacity duration-200" data-modal-overlay></div>

    <div class="relative bg-white rounded-xl shadow-2xl w-[92%] max-w-2xl p-5 sm:p-6 transform transition duration-200 scale-95 opacity-0" data-modal-panel>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
            <button
                type="button"
                class="text-slate-400 hover:text-slate-600 transition"
                data-close-modal="{{ $id }}"
                aria-label="Close modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        {{ $slot }}
    </div>
</div>
