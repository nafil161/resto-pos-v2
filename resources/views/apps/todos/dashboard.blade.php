@php
use App\Models\Todo;
$todosCount = Todo::where('user_id', auth()->id())->count();
$next = Todo::where('user_id', auth()->id())->where('due_at', '>=', now())->orderBy('due_at')->first();
@endphp

<div class="mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $todosCount }} Todos</h5>
            <small class="text-muted">Next: {{ $next?->title ?? '—' }}</small>
        </div>
        <div>
            <a href="{{ route('apps.open', $slug) }}" wire:navigate class="btn btn-sm btn-outline-primary">Open</a>
        </div>
    </div>
</div>