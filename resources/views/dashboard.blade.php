@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


@if (session('success'))
<div class="alert alert-success alert-dismissible mb-4" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Tabs --}}
<ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ count($myApps) > 0 ? 'active' : '' }}" id="my-apps-tab" data-bs-toggle="tab"
            data-bs-target="#my-apps" type="button" role="tab">
            <i class="ti tabler-layout-grid me-1"></i> My Apps
            @if (count($myApps))
            <span class="badge bg-primary ms-1">{{ count($myApps) }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ count($myApps) === 0 ? 'active' : '' }}" id="all-apps-tab" data-bs-toggle="tab"
            data-bs-target="#all-apps" type="button" role="tab">
            <i class="ti tabler-apps me-1"></i> All Apps
        </button>
    </li>
</ul>

<div class="tab-content" id="dashboardTabContent">

    {{-- ── My Apps ──────────────────────────────────────────── --}}
    <div class="tab-pane fade {{ count($myApps) > 0 ? 'show active' : '' }}" id="my-apps" role="tabpanel">
        @if (count($myApps) === 0)
        <div class="text-center py-5">
            <i class="ti tabler-apps-off" style="font-size: 3rem; opacity:.3;"></i>
            <p class="mt-3 text-muted">You haven't added any apps yet.</p>
            <button class="btn btn-primary" data-bs-toggle="tab" data-bs-target="#all-apps">
                Browse All Apps
            </button>
        </div>
        @else
        <div class="row g-4">
            @foreach ($myApps as $slug => $app)
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded {{ $app['icon_bg'] }}">
                                    <i class="icon-base ti {{ $app['icon'] }} icon-26px"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $app['name'] }}</h6>
                                <small class="text-muted">{{ $app['is_free'] ? 'Free' : 'Paid' }}</small>
                            </div>
                        </div>

                        {{-- If app provides a dashboard partial, include it here to show key metrics --}}
                        @if (view()->exists('apps.' . $slug . '.dashboard'))
                            @include('apps.' . $slug . '.dashboard', ['slug' => $slug, 'app' => $app])
                        @else
                            <p class="text-muted small flex-grow-1">{{ $app['description'] }}</p>
                        @endif

                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('apps.open', $slug) }}" wire:navigate class="btn btn-primary btn-sm flex-grow-1">
                                <i class="ti tabler-player-play me-1"></i> Open
                            </a>
                            <form method="POST" action="{{ route('apps.unsubscribe', $slug) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm d-block" title="Remove from My Apps">
                                    <i class="ti tabler-x"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ── All Apps ─────────────────────────────────────────── --}}
    <div class="tab-pane fade {{ count($myApps) === 0 ? 'show active' : '' }}" id="all-apps" role="tabpanel">
        <div class="row g-4">
            @foreach ($allApps as $slug => $app)
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <span class="avatar-initial rounded {{ $app['icon_bg'] }}">
                                    <i class="icon-base ti {{ $app['icon'] }} icon-26px"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $app['name'] }}</h6>
                            </div>
                            <span class="badge {{ $app['is_free'] ? 'bg-label-success' : 'bg-label-warning' }} ms-2">
                                {{ $app['is_free'] ? 'FREE' : 'PAID' }}
                            </span>
                        </div>

                        <p class="text-muted small flex-grow-1">{{ $app['description'] }}</p>

                        <div class="mt-2">
                            @if (in_array($slug, $myAppSlugs))
                            <a href="{{ route('apps.open', $slug) }}" wire:navigate
                                class="btn btn-primary btn-sm w-100">
                                <i class="ti tabler-player-play me-1"></i> Open App
                            </a>
                            @else
                            <form method="POST" action="{{ route('apps.subscribe', $slug) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="ti tabler-plus me-1"></i> Add to My Apps
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection