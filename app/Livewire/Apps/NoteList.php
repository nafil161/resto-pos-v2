<?php

namespace App\Livewire\Apps;

use App\Models\Note;
use Livewire\Component;

class NoteList extends Component
{
    public string $search = '';

    public function togglePin(int $id): void
    {
        $note = Note::findOrFail($id);
        abort_if($note->user_id !== auth()->id(), 403);
        $note->update(['is_pinned' => !$note->is_pinned]);
    }

    public function delete(int $id): void
    {
        $note = Note::findOrFail($id);
        abort_if($note->user_id !== auth()->id(), 403);
        $note->delete();
    }

    public function render()
    {
        $notes = auth()->user()->notes()
            ->when(
                $this->search,
                fn($q) => $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%')
            )
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.apps.note-list', compact('notes'));
    }
}
