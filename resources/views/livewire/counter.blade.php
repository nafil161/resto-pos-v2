<div>
    <p class="mb-3 text-body-secondary">
        This counter runs entirely server-side via Livewire 4 — no page refresh needed.
    </p>

    <div class="d-flex align-items-center gap-3">
        <button wire:click="decrement" class="btn btn-outline-secondary">
            <i class="ti tabler-minus"></i> Decrement
        </button>

        <h2 class="mb-0 px-4">{{ $count }}</h2>

        <button wire:click="increment" class="btn btn-primary">
            <i class="ti tabler-plus"></i> Increment
        </button>

        {{-- <button wire:click="reset" class="btn btn-outline-danger ms-3">
            <i class="ti tabler-refresh"></i> Reset
        </button> --}}
    </div>

    <div wire:loading class="mt-2 text-secondary small">
        <span class="spinner-border spinner-border-sm me-1" role="status"></span> Updating…
    </div>
</div>