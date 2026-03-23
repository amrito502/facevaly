@extends('admin.master')

@section('content')
<div class="container-fluid py-4">
    <div class="container">

        {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">Users</h1>
                <p class="mb-0 text-muted">Manage users and assign roles</p>
            </div>

            <a href="{{ route('users.create') }}" class="btn btn-info text-white px-4">
                <i class="fa-solid fa-plus me-2"></i>New User
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-start" role="alert">
                <i class="fa-solid fa-circle-check me-2 mt-1"></i>
                <div>
                    <strong>Success:</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-start" role="alert">
                <i class="fa-solid fa-circle-xmark me-2 mt-1"></i>
                <div>
                    <strong>Error:</strong> {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-3" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fa-solid fa-triangle-exclamation me-2 mt-1"></i>
                    <div>
                        <strong class="d-block mb-2">Please fix the following:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            {{-- Search & Total --}}
            <div class="card-header bg-white border-bottom px-3 px-md-4 py-3">
                <form method="GET" action="{{ route('users.index') }}" class="row align-items-center g-3">
                    <div class="col-12 col-md">
                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-center">
                            <div class="input-group" style="max-width: 320px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                                </span>
                                <input type="text"
                                       name="q"
                                       value="{{ request('q') }}"
                                       placeholder="Search users..."
                                       class="form-control border-start-0"
                                       style="box-shadow: none;">
                            </div>

                            <button type="submit" class="btn btn-outline-secondary px-3">
                                <i class="fa-solid fa-search me-1"></i>Search
                            </button>
                        </div>
                    </div>

                    <div class="col-12 col-md-auto text-md-end">
                        <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2">
                            Total: {{ $users->total() }}
                        </span>
                    </div>
                </form>
            </div>

            {{-- Desktop Table --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-uppercase small text-muted">User</th>
                            <th class="px-4 py-3 text-uppercase small text-muted">Role</th>
                            <th class="px-4 py-3 text-uppercase small text-muted text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                {{-- User --}}
                                <td class="px-4 py-3">
                                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>

                                {{-- Role --}}
                                <td class="px-4 py-3">
                                    @php
                                        $roleName = optional($user->roles->first())->name;
                                    @endphp

                                    @if($roleName)
                                        <span class="badge rounded-pill text-bg-light border fw-normal px-3 py-2">
                                            {{ $roleName }}
                                        </span>
                                    @else
                                        <span class="text-muted small">No role</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                        </a>

                                        <form method="POST" action="{{ route('users.destroy') }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="fa-regular fa-folder-open me-2"></i>No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="d-block d-md-none bg-white">
                @forelse($users as $user)
                    <div class="border-bottom p-3">
                        <div class="mb-2">
                            <div class="fw-semibold text-dark">{{ $user->name }}</div>
                            <small class="text-muted d-block">{{ $user->email }}</small>
                        </div>

                        @php $roleName = optional($user->roles->first())->name; @endphp

                        <div class="mb-3">
                            @if($roleName)
                                <span class="badge rounded-pill text-bg-light border fw-normal px-3 py-2">
                                    {{ $roleName }}
                                </span>
                            @else
                                <span class="text-muted small">No role</span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                            </a>

                            <form method="POST" action="{{ route('users.destroy') }}"
                                  onsubmit="return confirm('Delete this user?');">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="fa-solid fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="fa-regular fa-folder-open me-2"></i>No users found.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-white border-top px-3 px-md-4 py-3">
                {{ $users->withQueryString()->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
