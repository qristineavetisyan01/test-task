@if ($paginator->hasPages())
    @php
        $elements = array_filter([
            $paginator->getUrlRange(1, $paginator->lastPage()),
        ]);
    @endphp

    <nav class="mt-6 flex justify-center" role="navigation" aria-label="Pagination Navigation">
        <div class="inline-flex items-center gap-1 rounded-xl bg-white/80 p-1 shadow-sm ring-1 ring-slate-200">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm rounded-lg text-slate-400 cursor-not-allowed">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="js-page-link px-3 py-2 text-sm rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">Prev</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 py-2 text-sm text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 text-sm rounded-lg bg-blue-600 text-white font-medium shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="js-page-link px-3 py-2 text-sm rounded-lg text-slate-700 hover:bg-slate-100 hover:text-slate-900 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="js-page-link px-3 py-2 text-sm rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">Next</a>
            @else
                <span class="px-3 py-2 text-sm rounded-lg text-slate-400 cursor-not-allowed">Next</span>
            @endif
        </div>
    </nav>
@endif
