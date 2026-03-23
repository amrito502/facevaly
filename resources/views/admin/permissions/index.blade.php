@extends('admin.master')

@section('content')
<div class="container-fluid py-4">
    <div class="container">

        {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">Permissions</h1>
                <p class="mb-0 text-muted">Create and manage permissions</p>
            </div>

            <div>
                <a href="{{ route('permissions.create') }}" class="btn btn-info text-white px-4">
                    <i class="fa-solid fa-plus me-2"></i>New Permission
                </a>
            </div>
        </div>

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
                                       placeholder="Search..."
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
                            Total: {{ $permissions->total() }}
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
                            <th class="px-4 py-3 text-uppercase small text-muted text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-semibold text-dark">{{ $permission->name }}</div>
                                    <small class="text-muted">
                                        <i class="fa-solid fa-hashtag me-1"></i>ID: {{ $permission->id }}
                                    </small>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('permissions.edit', $permission) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('permissions.destroy', $permission) }}"
                                              onsubmit="return confirm('Delete this permission?');">
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
                                <td colspan="2" class="text-center py-5 text-muted">
                                    <i class="fa-regular fa-folder-open me-2"></i>No permissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="d-block d-md-none bg-white">
                @forelse($permissions as $permission)
                    <div class="border-bottom p-3">
                        <div class="mb-2">
                            <div class="fw-semibold text-dark">{{ $permission->name }}</div>
                            <small class="text-muted">
                                <i class="fa-solid fa-hashtag me-1"></i>ID: {{ $permission->id }}
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('permissions.edit', $permission) }}"
                               class="btn btn-sm btn-outline-primary w-50">
                                <i class="fa-solid fa-pen-to-square me-1"></i>Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('permissions.destroy', $permission) }}"
                                  class="w-50"
                                  onsubmit="return confirm('Delete this permission?');">
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
                        <i class="fa-regular fa-folder-open me-2"></i>No permissions found.
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="card-footer bg-white border-top px-3 px-md-4 py-3">
                {{ $permissions->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
