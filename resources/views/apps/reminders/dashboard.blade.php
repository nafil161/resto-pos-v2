@php
use App\Models\Reminder;
$remindersCount = Reminder::where('user_id', auth()->id())->count();
$next = Reminder::where('user_id', auth()->id())->where('remind_at', '>=', now())->orderBy('remind_at')->first();
@endphp

<div class="mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $remindersCount }} Reminders</h5>
            <small class="text-muted">Next: {{ $next?->title ?? '—' }}</small>
        </div>
    </div>
</div>