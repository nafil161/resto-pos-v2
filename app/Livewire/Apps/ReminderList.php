<?php

namespace App\Livewire\Apps;

use App\Models\Reminder;
use Livewire\Component;

class ReminderList extends Component
{
    public function toggle(int $id): void
    {
        $reminder = Reminder::findOrFail($id);
        abort_if($reminder->user_id !== auth()->id(), 403);
        $reminder->update(['is_completed' => !$reminder->is_completed]);
    }

    public function delete(int $id): void
    {
        $reminder = Reminder::findOrFail($id);
        abort_if($reminder->user_id !== auth()->id(), 403);
        $reminder->delete();
    }

    public function render()
    {
        $pending = auth()->user()->reminders()
            ->where('is_completed', false)
            ->orderBy('remind_at')
            ->get();

        $completed = auth()->user()->reminders()
            ->where('is_completed', true)
            ->orderByDesc('updated_at')
            ->get();

        return view('livewire.apps.reminder-list', compact('pending', 'completed'));
    }
}
