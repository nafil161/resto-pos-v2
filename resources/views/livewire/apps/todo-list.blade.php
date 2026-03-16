<div>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between py-4 mb-2">
        <h4 class="mb-0">
            <i class="ti tabler-check me-2 text-primary"></i> Todos
        </h4>
        <a href="{{ route('todos.create') }}" wire:navigate class="btn btn-primary">
            <i class="ti tabler-plus me-1"></i> New Todo
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search todos…">
    </div>

    <div class="list-group">
        @forelse ($todos as $todo)
        <div
            class="list-group-item d-flex justify-content-between align-items-start {{ $todo->is_completed ? 'list-group-item-success' : '' }}">
            <div class="d-flex align-items-start gap-3">
                <input class="form-check-input mt-1" type="checkbox" wire:click="toggle({{ $todo->id }})" {{
                    $todo->is_completed ? 'checked' : '' }}
                title="Toggle complete"
                style="cursor:pointer">
                <div>
                    <h6 class="mb-1 {{ $todo->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                        {{ $todo->title }}
                    </h6>
                    @if ($todo->description)
                    <p class="text-muted small mb-1">{{ $todo->description }}</p>
                    @endif
                    @if ($todo->due_at)
                    <small class="text-muted">
                        <i class="ti tabler-calendar me-1"></i>Due: {{ $todo->due_at->format('d M Y') }}
                        @if($todo->due_at->isPast() && !$todo->is_completed)
                        <span class="badge bg-label-danger ms-1">Overdue</span>
                        @endif
                    </small>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2 align-items-start">
                <span class="badge {{ $todo->is_completed ? 'bg-success' : 'bg-secondary' }}">
                    {{ $todo->is_completed ? 'Done' : 'Pending' }}
                </span>
                <a href="{{ route('todos.edit', $todo) }}" wire:navigate class="btn btn-sm btn-outline-primary">
                    <i class="ti tabler-pencil"></i>
                </a>
                <button wire:click="delete({{ $todo->id }})" wire:confirm="Remove this todo?"
                    class="btn btn-sm btn-outline-danger">
                    <i class="ti tabler-trash"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="list-group-item text-center text-muted py-5">
            <i class="ti tabler-check" style="font-size:2rem;opacity:.3;"></i>
            <p class="mt-2 mb-0">
                @if ($search)
                No todos match your search.
                @else
                No todos yet.
                <a href="{{ route('todos.create') }}" wire:navigate>Add your first todo</a>
                @endif
            </p>
        </div>
        @endforelse
    </div>
</div>