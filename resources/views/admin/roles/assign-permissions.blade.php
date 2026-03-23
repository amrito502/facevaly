@extends('admin.master')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<div class="container-fluid py-4">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 small text-muted mb-2">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Roles & Permissions</span>
                    <span>/</span>
                    <span class="fw-semibold text-dark">Assign Permissions</span>
                </div>

                <h1 class="h3 mb-1 text-dark fw-bold">Assign Permissions</h1>
                <p class="mb-0 text-muted">
                    Role: <span class="fw-semibold text-dark">{{ $role->name }}</span>
                </p>
            </div>

            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back
            </a>
        </div>

        {{-- Success --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-start" role="alert">
                <i class="fa-solid fa-circle-check me-2 mt-1"></i>
                <div>
                    <strong>Success:</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Errors --}}
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
            <form method="POST" action="{{ route('roles.permissions.update', $role) }}">
                @csrf

                {{-- Action Bar --}}
                <div class="card-header bg-white border-bottom px-3 px-md-4 py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="text-muted small">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            Select permissions grouped by module
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" onclick="toggleAll(true)" class="btn btn-outline-primary btn-sm">
                                <i class="fa-solid fa-square-check me-1"></i>Select All
                            </button>

                            <button type="button" onclick="toggleAll(false)" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-eraser me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Permission Groups --}}
                <div class="card-body p-3 p-md-4">
                    <div class="row g-4">
                        @foreach($groupedPermissions as $groupName => $perms)
                            @php
                                $groupHash = md5($groupName);
                                $labelName = $groupName ?: 'General';
                            @endphp

                            <div class="col-12">
                                <div class="card border rounded-4">

                                    {{-- Group Header --}}
                                    <div class="card-header bg-light border-bottom px-3 px-md-4 py-3">
                                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                                            <div class="form-check m-0">
                                                <input type="checkbox"
                                                       class="form-check-input group-master"
                                                       id="group-master-{{ $groupHash }}"
                                                       data-group="{{ $groupHash }}"
                                                       onchange="toggleGroupByMaster(this)">
                                                <label class="form-check-label" for="group-master-{{ $groupHash }}">
                                                    <span class="fw-semibold text-dark d-block">{{ $labelName }}</span>
                                                    <span class="text-muted small">{{ $perms->count() }} permissions</span>
                                                </label>
                                            </div>

                                            <div class="d-flex flex-wrap gap-2">
                                                <button type="button"
                                                        onclick="toggleGroup('{{ $groupHash }}', true)"
                                                        class="btn btn-outline-primary btn-sm">
                                                    <i class="fa-solid fa-check me-1"></i>Select Group
                                                </button>

                                                <button type="button"
                                                        onclick="toggleGroup('{{ $groupHash }}', false)"
                                                        class="btn btn-outline-secondary btn-sm">
                                                    <i class="fa-solid fa-xmark me-1"></i>Clear Group
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Permissions --}}
                                    <div class="card-body p-3 p-md-4">
                                        <div class="row g-3">
                                            @foreach($perms as $perm)
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <label class="permission-box border rounded-3 p-3 w-100 h-100 bg-white">
                                                        <div class="form-check m-0 d-flex align-items-start gap-2">
                                                            <input type="checkbox"
                                                                   class="form-check-input perm-checkbox group-{{ $groupHash }} mt-1"
                                                                   name="permissions[]"
                                                                   value="{{ $perm->name }}"
                                                                   {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}
                                                                   onchange="syncMaster('{{ $groupHash }}')">

                                                            <div class="min-w-0">
                                                                <div class="fw-semibold text-dark small text-break">
                                                                    {{ ucfirst(last(explode('.', $perm->name))) }}
                                                                </div>
                                                                <div class="text-muted small text-break">
                                                                    {{ $perm->name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white border-top px-3 px-md-4 py-3 text-end">
                    <button type="submit" class="btn btn-info text-white px-4">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
    .permission-box {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    .permission-box:hover {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .permission-box:has(input:checked) {
        background-color: #eef8ff !important;
        border-color: #0dcaf0 !important;
    }
</style>

<script>
    function toggleAll(state) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = state);
        document.querySelectorAll('.group-master').forEach(cb => cb.checked = state);
    }

    function toggleGroup(hash, state) {
        document.querySelectorAll('.group-' + hash).forEach(cb => cb.checked = state);
        const master = document.querySelector('.group-master[data-group="' + hash + '"]');
        if (master) master.checked = state;
    }

    function toggleGroupByMaster(el) {
        toggleGroup(el.dataset.group, el.checked);
    }

    function syncMaster(hash) {
        const boxes = [...document.querySelectorAll('.group-' + hash)];
        const master = document.querySelector('.group-master[data-group="' + hash + '"]');
        if (!master) return;
        master.checked = boxes.length && boxes.every(cb => cb.checked);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.group-master').forEach(master => {
            syncMaster(master.dataset.group);
        });
    });
</script>

@endsection
