@extends('admin.master')

@section('content')
<div class="container-fluid py-4">
    <div class="container">

        {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">Roles</h1>
                <p class="mb-0 text-muted">Manage roles and assign permissions</p>
            </div>

            <div>
                <a href="{{ route('roles.create') }}" class="btn btn-info text-white px-4">
                    <i class="fa-solid fa-plus me-2"></i>New Role
                </a>
            </div>
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

            {{-- Search Area --}}
            <div class="card-header bg-white border-bottom px-3 px-md-4 py-3">
                <form method="GET" class="row align-items-center g-3">
                    <div class="col-12 col-md">
                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-stretch align-items-sm-center">
                            <div class="input-group" style="max-width: 320px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                                </span>
                                <input type="text"
                                       name="q"
                                       value="{{ request('q') }}"
                                       placeholder="Search roles..."
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
                            Total: {{ $roles->total() }}
                        </span>
                    </div>
                </form>
            </div>

            {{-- Desktop Table --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-uppercase small text-muted">Name</th>
                            <th class="px-4 py-3 text-uppercase small text-muted">Permissions</th>
                            <th class="px-4 py-3 text-uppercase small text-muted text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                {{-- Role Name --}}
                                <td class="px-4 py-3">
                                    <div class="fw-semibold text-dark">{{ $role->name }}</div>
                                    <small class="text-muted">
                                        <i class="fa-solid fa-hashtag me-1"></i>ID: {{ $role->id }}
                                    </small>
                                </td>

                                {{-- Permissions --}}
                                <td class="px-4 py-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($role->permissions->take(6) as $perm)
                                            <span class="badge rounded-pill text-bg-light border fw-normal px-3 py-2">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach

                                        @if($role->permissions->count() > 6)
                                            <span class="text-muted small align-self-center">
                                                +{{ $role->permissions->count() - 6 }} more
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                        <a href="{{ route('roles.permissions.edit', $role) }}"
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fa-solid fa-key me-1"></i>Assign Permissions
                                        </a>

                                        <a href="{{ route('roles.edit', $role) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('roles.destroy', $role) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this role?');">
                                            @csrf
                                            @method('DELETE')
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
                                    <i class="fa-regular fa-folder-open me-2"></i>No roles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="d-block d-md-none bg-white">
                @forelse($roles as $role)
                    <div class="border-bottom p-3">
                        <div class="mb-2">
                            <div class="fw-semibold text-dark">{{ $role->name }}</div>
                            <small class="text-muted">
                                <i class="fa-solid fa-hashtag me-1"></i>ID: {{ $role->id }}
                            </small>
                        </div>

                        <div class="mb-3 d-flex flex-wrap gap-2">
                            @foreach($role->permissions->take(6) as $perm)
                                <span class="badge rounded-pill text-bg-light border fw-normal px-3 py-2">
                                    {{ $perm->name }}
                                </span>
                            @endforeach

                            @if($role->permissions->count() > 6)
                                <span class="text-muted small align-self-center">
                                    +{{ $role->permissions->count() - 6 }} more
                                </span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('roles.permissions.edit', $role) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="fa-solid fa-key me-1"></i>Assign Permissions
                            </a>

                            <a href="{{ route('roles.edit', $role) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('roles.destroy', $role) }}"
                                  onsubmit="return confirm('Delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="fa-solid fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="fa-regular fa-folder-open me-2"></i>No roles found.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-white border-top px-3 px-md-4 py-3">
                {{ $roles->withQueryString()->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
