<div>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between py-4 mb-2">
        <h4 class="mb-0">
            <i class="ti tabler-bell me-2 text-warning"></i> Reminders
        </h4>
        <a href="{{ route('reminders.create') }}" wire:navigate class="btn btn-primary">
            <i class="ti tabler-plus me-1"></i> New Reminder
        </a>
    </div>

    {{-- Pending --}}
    <h6 class="text-uppercase text-muted small mb-3">
        Pending <span class="badge bg-label-warning ms-1">{{ $pending->count() }}</span>
    </h6>

    @if ($pending->isEmpty())
    <div class="alert alert-success mb-4">
        <i class="ti tabler-check me-2"></i>All caught up! No pending reminders.
    </div>
    @else
    <div class="row g-3 mb-4">
        @foreach ($pending as $reminder)
        @php
        $borderClass = 'border-success';
        if ($reminder->remind_at) {
        if ($reminder->remind_at->isPast()) {
        $borderClass = 'border-danger';
        } elseif ($reminder->remind_at->isToday()) {
        $borderClass = 'border-warning';
        }
        }
        @endphp
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100 shadow-sm border-start border-3 {{ $borderClass }}">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="mb-0 fw-semibold">{{ $reminder->title }}</h6>
                        <button wire:click="toggle({{ $reminder->id }})" class="btn btn-md btn-outline-success border-1"
                            title="Mark as done">
                            <i class="ti tabler-circle-check"></i>
                        </button>
                    </div>

                    @if ($reminder->description)
                    <p class="text-muted small flex-grow-1 mb-2">{{ Str::limit($reminder->description, 100) }}</p>
                    @endif

                    @if ($reminder->remind_at)
                    <p class="mb-2">
                        <i class="ti tabler-clock me-1 text-warning"></i>
                        <small class="{{ $reminder->remind_at->isPast() ? 'text-danger fw-semibold' : 'text-muted' }}">
                            {{ $reminder->remind_at->format('d M Y, h:i A') }}
                            @if($reminder->remind_at->isPast())
                            <span class="badge bg-label-danger ms-1">Overdue</span>
                            @endif
                        </small>
                    </p>
                    @endif

                    <div class="d-flex gap-2 mt-auto align-items-center">
                        <a href="{{ route('reminders.edit', $reminder) }}" wire:navigate
                            class="btn btn-sm btn-outline-secondary flex-grow-1">
                            <i class="ti tabler-pencil me-1"></i> Edit
                        </a>
                        <button wire:click="delete({{ $reminder->id }})" wire:confirm="Delete this reminder?"
                            class="btn btn-sm btn-outline-danger">
                            <i class="ti tabler-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Completed --}}
    @if ($completed->isNotEmpty())
    <h6 class="text-uppercase text-muted small mb-3">
        Completed <span class="badge bg-label-success ms-1">{{ $completed->count() }}</span>
    </h6>
    <div class="row g-3">
        @foreach ($completed as $reminder)
        <div class="col-sm-6 col-xl-4">
            <div class="card h-100 shadow-sm opacity-75">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="mb-0 fw-semibold text-decoration-line-through text-muted">
                            {{ $reminder->title }}
                        </h6>
                        <button wire:click="toggle({{ $reminder->id }})"
                            class="btn btn-sm btn-outline-secondary border-0" title="Mark as pending">
                            <i class="ti tabler-rotate-clockwise"></i>
                        </button>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <button wire:click="delete({{ $reminder->id }})" wire:confirm="Delete this reminder?"
                            class="btn btn-sm btn-outline-danger">
                            <i class="ti tabler-trash me-1"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>