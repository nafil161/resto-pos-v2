<?php

namespace App\Livewire\Apps;

use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\Computed;

class TodoList extends Component
{
    public string $search = '';

    public function toggle(int $id): void
    {
        $todo = Todo::findOrFail($id);
        abort_if($todo->user_id !== auth()->id(), 403);
        $todo->update(['is_completed' => !$todo->is_completed]);
    }

    public function delete(int $id): void
    {
        $todo = Todo::findOrFail($id);
        abort_if($todo->user_id !== auth()->id(), 403);
        $todo->delete();
    }

    public function render()
    {
        $todos = auth()->user()->todos()
            ->when(
                $this->search,
                fn($q) => $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->orderBy('is_completed')
            ->orderBy('due_at')
            ->get();

        return view('livewire.apps.todo-list', compact('todos'));
    }
}
