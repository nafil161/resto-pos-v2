@extends('layouts.app')

@section('title', 'New Reminder')

@section('content')
<div class="d-flex align-items-center py-4 mb-2 gap-3">
    <a href="{{ route('reminders.index') }}" wire:navigate class="btn btn-outline-secondary btn-sm">
        <i class="ti tabler-arrow-left"></i>
    </a>
    <h4 class="mb-0">New Reminder</h4>
</div>

<div class="row">
    <div class="col-xl-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('reminders.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="e.g. Call dentist, Buy groceries…" autofocus>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Optional details…">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Remind me at</label>
                        <input type="datetime-local" name="remind_at" value="{{ old('remind_at') }}"
                            class="form-control @error('remind_at') is-invalid @enderror">
                        @error('remind_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti tabler-bell me-1"></i> Save Reminder
                        </button>
                        <a href="{{ route('reminders.index') }}" wire:navigate class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection