@extends('layouts.app')

@section('title', 'New Todo')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('todos.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Due at</label>
                <input type="datetime-local" name="due_at" class="form-control">
            </div>
            <button class="btn btn-primary">Create</button>
        </form>
    </div>
</div>
@endsection