<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodosController extends Controller
{
    public function index()
    {
        $todos = auth()->user()->todos()->orderBy('is_completed')->orderBy('due_at')->get();
        return view('apps.todos.index', compact('todos'));
    }

    public function create()
    {
        return view('apps.todos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_at' => 'nullable|date',
        ]);

        $data['user_id'] = auth()->id();
        Todo::create($data);

        return redirect()->route('todos.index')->with('success', 'Todo added');
    }

    public function edit(Todo $todo)
    {
        $this->authorizeTodo($todo);
        return view('apps.todos.edit', compact('todo'));
    }

    public function update(Request $request, Todo $todo)
    {
        $this->authorizeTodo($todo);
        // If this request only toggles completion, accept that without requiring the title
        $onlyToggle = $request->has('is_completed') && !$request->hasAny(['title', 'description', 'due_at']);

        if ($onlyToggle) {
            $todo->update(['is_completed' => $request->boolean('is_completed')]);
            return redirect()->route('todos.index');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_at' => 'nullable|date',
            'is_completed' => 'sometimes|boolean',
        ]);

        $todo->update($data);

        return redirect()->route('todos.index')->with('success', 'Todo updated');
    }

    public function destroy(Todo $todo)
    {
        $this->authorizeTodo($todo);
        $todo->delete();
        return redirect()->route('todos.index')->with('success', 'Todo removed');
    }

    protected function authorizeTodo(Todo $todo)
    {
        if ($todo->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
