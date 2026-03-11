@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <h4 class="py-4 mb-6">Dashboard</h4>

  <div class="row g-4 mb-4">
    <!-- Stats cards -->
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-secondary">Session</span>
              <div class="d-flex align-items-center my-1">
                <h4 class="mb-0 me-2">21,459</h4>
                <p class="text-success mb-0">(+29%)</p>
              </div>
              <small class="text-secondary">Total Users</small>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="icon-base ti tabler-users icon-26px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-secondary">Paid Users</span>
              <div class="d-flex align-items-center my-1">
                <h4 class="mb-0 me-2">4,567</h4>
                <p class="text-success mb-0">(+18%)</p>
              </div>
              <small class="text-secondary">Last week analytics</small>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-danger">
                <i class="icon-base ti tabler-user-plus icon-26px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-secondary">Active Users</span>
              <div class="d-flex align-items-center my-1">
                <h4 class="mb-0 me-2">19,860</h4>
                <p class="text-danger mb-0">(-14%)</p>
              </div>
              <small class="text-secondary">Last week analytics</small>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-success">
                <i class="icon-base ti tabler-user-check icon-26px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span class="text-secondary">Pending Users</span>
              <div class="d-flex align-items-center my-1">
                <h4 class="mb-0 me-2">237</h4>
                <p class="text-success mb-0">(+42%)</p>
              </div>
              <small class="text-secondary">Last week analytics</small>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-warning">
                <i class="icon-base ti tabler-user-exclamation icon-26px"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Livewire counter component example -->
  <div class="row g-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Live Counter (Livewire Component)</h5>
        </div>
        <div class="card-body">
          @livewire('counter')
        </div>
      </div>
    </div>
  </div>
@endsection
