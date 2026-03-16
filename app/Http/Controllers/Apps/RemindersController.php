<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RemindersController extends Controller
{
    public function index(): View
    {
        $pending = auth()->user()->reminders()
            ->where('is_completed', false)
            ->orderBy('remind_at')
            ->get();

        $completed = auth()->user()->reminders()
            ->where('is_completed', true)
            ->orderByDesc('updated_at')
            ->get();

        return view('apps.reminders.index', compact('pending', 'completed'));
    }

    public function create(): View
    {
        return view('apps.reminders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'remind_at'   => ['nullable', 'date'],
        ]);

        auth()->user()->reminders()->create($data);

        return redirect()->route('reminders.index')->with('success', 'Reminder created!');
    }

    public function edit(Reminder $reminder): View
    {
        abort_unless($reminder->user_id === auth()->id(), 403);

        return view('apps.reminders.edit', compact('reminder'));
    }

    public function update(Request $request, Reminder $reminder): RedirectResponse
    {
        abort_unless($reminder->user_id === auth()->id(), 403);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'remind_at'   => ['nullable', 'date'],
        ]);

        $reminder->update($data);

        return redirect()->route('reminders.index')->with('success', 'Reminder updated!');
    }

    public function destroy(Reminder $reminder): RedirectResponse
    {
        abort_unless($reminder->user_id === auth()->id(), 403);

        $reminder->delete();

        return redirect()->route('reminders.index')->with('success', 'Reminder deleted.');
    }

    public function toggle(Reminder $reminder): RedirectResponse
    {
        abort_unless($reminder->user_id === auth()->id(), 403);

        $reminder->update(['is_completed' => ! $reminder->is_completed]);

        return back();
    }
}
