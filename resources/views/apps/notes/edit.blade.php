@extends('layouts.app')

@section('title', 'Edit Note')

@section('content')
<div class="d-flex align-items-center py-4 mb-2 gap-3">
    <a href="{{ route('notes.index') }}" wire:navigate class="btn btn-outline-secondary btn-sm">
        <i class="ti tabler-arrow-left"></i>
    </a>
    <h4 class="mb-0">Edit Note</h4>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('notes.update', $note) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $note->title) }}"
                            class="form-control @error('title') is-invalid @enderror" placeholder="Note title…"
                            autofocus>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Content</label>
                        <textarea name="content" rows="8" class="form-control @error('content') is-invalid @enderror"
                            placeholder="Write your note here…">{{ old('content', $note->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Colour Tag</label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach (['default' => 'None', 'primary' => 'Blue', 'success' => 'Green', 'warning' =>
                            'Yellow', 'danger' => 'Red', 'info' => 'Cyan'] as $val => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="color" id="color_{{ $val }}"
                                    value="{{ $val }}" {{ old('color', $note->color) === $val ? 'checked' : '' }}>
                                <label class="form-check-label" for="color_{{ $val }}">
                                    {{ $label }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti tabler-device-floppy me-1"></i> Update Note
                            </button>
                            <a href="{{ route('notes.index') }}" wire:navigate class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-danger"
                                onclick="document.getElementById('delete-note-form').submit();"
                                onclick="return confirm('Delete this note?')">
                                <i class="ti tabler-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Delete form lives OUTSIDE the update form to avoid nested form issues --}}
                <form id="delete-note-form" method="POST" action="{{ route('notes.destroy', $note) }}"
                    onsubmit="return confirm('Delete this note?')">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection