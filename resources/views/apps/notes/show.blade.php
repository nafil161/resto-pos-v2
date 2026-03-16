@extends('layouts.app')

@section('title', $note->title)

@section('content')
@php
$colorMap = [
'primary' => 'border-primary text-primary',
'success' => 'border-success text-success',
'warning' => 'border-warning text-warning',
'danger' => 'border-danger text-danger',
'info' => 'border-info text-info',
'default' => '',
];
$colorClass = $colorMap[$note->color] ?? '';
@endphp

<div class="d-flex align-items-center py-4 mb-2 gap-3">
    <a href="{{ route('notes.index') }}" wire:navigate class="btn btn-outline-secondary btn-sm">
        <i class="ti tabler-arrow-left"></i>
    </a>
    <h4 class="mb-0 flex-grow-1">{{ $note->title }}</h4>
    @if($note->is_pinned)
    <span class="badge bg-label-warning"><i class="ti tabler-pin me-1"></i>Pinned</span>
    @endif
    <a href="{{ route('notes.edit', $note) }}" wire:navigate class="btn btn-outline-primary btn-sm">
        <i class="ti tabler-pencil me-1"></i> Edit
    </a>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card shadow-sm {{ $colorClass ? 'border-start border-3 ' . explode(' ', $colorClass)[0] : '' }}">
            <div class="card-body">
                @if ($note->content)
                <p style="white-space: pre-line; line-height: 1.8;">{{ $note->content }}</p>
                @else
                <p class="text-muted fst-italic">This note has no content.</p>
                @endif
                <hr>
                <small class="text-muted">
                    Created {{ $note->created_at->format('d M Y, h:i A') }}
                    @if ($note->updated_at->ne($note->created_at))
                    · Updated {{ $note->updated_at->diffForHumans() }}
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>
@endsection