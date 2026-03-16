<div>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between py-4 mb-2">
        <h4 class="mb-0">
            <i class="ti tabler-notes me-2 text-primary"></i> Notes
        </h4>
        <a href="{{ route('notes.create') }}" wire:navigate class="btn btn-primary">
            <i class="ti tabler-plus me-1"></i> New Note
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search notes…">
    </div>

    @if ($notes->isEmpty())
    <div class="text-center py-5">
        <i class="ti tabler-notes" style="font-size: 3rem; opacity:.3;"></i>
        <p class="mt-3 text-muted">
            @if ($search)
            No notes match your search.
            @else
            No notes yet. <a href="{{ route('notes.create') }}" wire:navigate>Create your first one</a>
            @endif
        </p>
    </div>
    @else
    <div class="row g-4">
        @foreach ($notes as $note)
        @php
        $colorMap = [
        'primary' => 'border-primary',
        'success' => 'border-success',
        'warning' => 'border-warning',
        'danger' => 'border-danger',
        'info' => 'border-info',
        'default' => '',
        ];
        $colorVars = [
        'primary' => 'var(--bs-primary)',
        'success' => 'var(--bs-success)',
        'warning' => 'var(--bs-warning)',
        'danger' => 'var(--bs-danger)',
        'info' => 'var(--bs-info)',
        'default' => 'transparent',
        ];
        $borderClass = $colorMap[$note->color] ?? '';
        $dotColor = $colorVars[$note->color] ?? 'transparent';
        @endphp
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100 shadow-sm {{ $borderClass ? 'border-start border-3 ' . $borderClass : '' }}">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                            <span
                                style="width:12px;height:12px;border-radius:50%;display:inline-block;background:{{ $dotColor }};box-shadow:0 0 0 1px rgba(0,0,0,0.04)"
                                aria-hidden></span>
                            <span>{{ $note->title }}</span>
                        </h6>
                        @if($note->is_pinned)
                        <i class="ti tabler-pin-filled text-warning" title="Pinned"></i>
                        @endif
                    </div>

                    @if ($note->content)
                    <p class="text-muted small flex-grow-1 mb-3" style="white-space: pre-line;">{{
                        Str::limit($note->content, 120) }}</p>
                    @else
                    <p class="text-muted small flex-grow-1 mb-3 fst-italic">No content</p>
                    @endif

                    <div class="d-flex gap-2 mt-auto align-items-center">
                        <a href="{{ route('notes.show', $note) }}" wire:navigate
                            class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="ti tabler-eye me-1"></i> View
                        </a>
                        <a href="{{ route('notes.edit', $note) }}" wire:navigate
                            class="btn btn-sm btn-outline-secondary">
                            <i class="ti tabler-pencil"></i>
                        </a>
                        <button wire:click="togglePin({{ $note->id }})"
                            class="btn btn-sm {{ $note->is_pinned ? 'btn-warning' : 'btn-outline-warning' }}"
                            title="{{ $note->is_pinned ? 'Unpin' : 'Pin' }}">
                            <i class="ti tabler-pin{{ $note->is_pinned ? '-filled' : '' }}"></i>
                        </button>
                        <button wire:click="delete({{ $note->id }})" wire:confirm="Delete this note?"
                            class="btn btn-sm btn-outline-danger">
                            <i class="ti tabler-trash"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2">{{ $note->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>