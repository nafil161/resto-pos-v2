@php
use App\Models\Note;
$notesCount = Note::where('user_id', auth()->id())->count();
$recent = Note::where('user_id', auth()->id())->latest()->first();
@endphp

<div class="mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $notesCount }} Notes</h5>
            <small class="text-muted">Recent: {{ $recent?->title ?? '—' }}</small>
        </div>
    </div>
</div>