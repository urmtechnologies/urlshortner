@if ($items->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <small>Showing {{ $items->firstItem() }}–{{ $items->lastItem() }} of {{ $items->total() }}</small>
        <div class="d-flex gap-2">
            @if ($items->onFirstPage())
            <button class="btn btn-sm btn-light border" disabled>Previous</button>@else<a
                    class="btn btn-sm btn-light border" href="{{ $items->previousPageUrl() }}">Previous</a>
            @endif
            @if ($items->hasMorePages())
            <a class="btn btn-sm btn-light border" href="{{ $items->nextPageUrl() }}">Next</a>@else<button
                    class="btn btn-sm btn-light border" disabled>Next</button>
            @endif
        </div>
    </div>
@endif
