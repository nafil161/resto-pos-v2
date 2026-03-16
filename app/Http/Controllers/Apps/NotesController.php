<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotesController extends Controller
{
    public function index(): View
    {
        $notes = auth()->user()->notes()
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return view('apps.notes.index', compact('notes'));
    }

    public function create(): View
    {
        return view('apps.notes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'color'   => ['nullable', 'string', 'in:default,primary,success,warning,danger,info'],
        ]);

        auth()->user()->notes()->create($data);

        return redirect()->route('notes.index')->with('success', 'Note created!');
    }

    public function show(Note $note): View
    {
        abort_unless($note->user_id === auth()->id(), 403);

        return view('apps.notes.show', compact('note'));
    }

    public function edit(Note $note): View
    {
        abort_unless($note->user_id === auth()->id(), 403);

        return view('apps.notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        abort_unless($note->user_id === auth()->id(), 403);

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'color'   => ['nullable', 'string', 'in:default,primary,success,warning,danger,info'],
        ]);

        $note->update($data);

        return redirect()->route('notes.index')->with('success', 'Note updated!');
    }

    public function destroy(Note $note): RedirectResponse
    {
        abort_unless($note->user_id === auth()->id(), 403);

        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted.');
    }

    public function togglePin(Note $note): RedirectResponse
    {
        abort_unless($note->user_id === auth()->id(), 403);

        $note->update(['is_pinned' => ! $note->is_pinned]);

        return back();
    }
}
