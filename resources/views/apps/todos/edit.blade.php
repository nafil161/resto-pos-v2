@extends('layouts.app')

@section('title', 'Edit Todo')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('todos.update', $todo) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" value="{{ $todo->title }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ $todo->description }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Due at</label>
                <input type="datetime-local" name="due_at" class="form-control"
                    value="{{ $todo->due_at?->format('Y-m-d\TH:i') }}">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_completed" class="form-check-input" value="1" {{ $todo->is_completed ?
                'checked' : '' }}>
                <label class="form-check-label">Completed</label>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection