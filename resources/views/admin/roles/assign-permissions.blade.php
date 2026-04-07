@extends('admin.master')

@section('content')
<style>
    .assign-permissions-page {
        font-size: 16px !important;
    }

    .permission-card {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .permission-card:hover {
        border-color: #cbd5e1;
        background-color: #f8fafc;
    }

    .permission-card.checked {
        border-color: #38bdf8;
        background-color: #f0f9ff;
        box-shadow: 0 0 0 1px rgba(56, 189, 248, 0.15);
    }
</style>

<div class="assign-permissions-page min-h-screen bg-gray-100 py-8">
    <div class="w-full px-4 md:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="mb-2 flex flex-wrap items-center gap-2 text-[13px] text-gray-500">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Roles & Permissions</span>
                    <span>/</span>
                    <span class="font-semibold text-gray-900">Assign Permissions</span>
                </div>

                <h1 class="text-[30px] font-bold leading-tight text-gray-900">
                    Assign Permissions
                </h1>
                <p class="mt-1 text-[15px] text-gray-500">
                    Role:
                    <span class="font-semibold text-gray-900">{{ $role->name }}</span>
                </p>
            </div>

            <a href="{{ route('roles.index') }}"
               class="inline-flex items-center justify-center rounded-[12px] border border-gray-300 bg-white px-5 py-3 text-[15px] font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>

        <!-- Success -->
        @if(session('success'))
            <div class="mb-5 rounded-[16px] border border-green-200 bg-green-50 px-4 py-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="pt-0.5 text-green-600">
                        <i class="fa-solid fa-circle-check text-[18px]"></i>
                    </div>
                    <div class="text-[15px] text-green-800">
                        <strong class="font-semibold">Success:</strong>
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Errors -->
        @if ($errors->any())
            <div class="mb-5 rounded-[16px] border border-red-200 bg-red-50 px-4 py-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="pt-0.5 text-red-600">
                        <i class="fa-solid fa-triangle-exclamation text-[18px]"></i>
                    </div>
                    <div class="text-[15px] text-red-800">
                        <strong class="mb-2 block font-semibold">Please fix the following:</strong>
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="overflow-hidden rounded-[20px] border border-gray-200 bg-white shadow-sm">
            <form method="POST" action="{{ route('roles.permissions.update', $role) }}">
                @csrf

                <!-- Action Bar -->
                <div class="border-b border-gray-200 px-4 py-4 md:px-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="text-[14px] text-gray-500">
                            <i class="fa-solid fa-circle-info mr-2"></i>
                            Select permissions grouped by module
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                    onclick="toggleAll(true)"
                                    class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-4 py-2 text-[14px] font-medium text-sky-700 transition hover:bg-sky-100">
                                <i class="fa-solid fa-square-check mr-2"></i>
                                Select All
                            </button>

                            <button type="button"
                                    onclick="toggleAll(false)"
                                    class="inline-flex items-center rounded-[10px] border border-gray-300 bg-white px-4 py-2 text-[14px] font-medium text-gray-700 transition hover:bg-gray-50">
                                <i class="fa-solid fa-eraser mr-2"></i>
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Permission Groups -->
                <div class="px-4 py-4 md:px-6 md:py-6">
                    <div class="space-y-5">
                        @foreach($groupedPermissions as $groupName => $perms)
                            @php
                                $groupHash = md5($groupName);
                                $labelName = $groupName ?: 'General';
                            @endphp

                            <div class="overflow-hidden rounded-[18px] border border-gray-200 bg-white">
                                <!-- Group Header -->
                                <div class="border-b border-gray-200 bg-gray-50 px-4 py-4 md:px-6">
                                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                                        <label for="group-master-{{ $groupHash }}" class="flex cursor-pointer items-start gap-3">
                                            <input type="checkbox"
                                                   class="group-master mt-1 h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500"
                                                   id="group-master-{{ $groupHash }}"
                                                   data-group="{{ $groupHash }}"
                                                   onchange="toggleGroupByMaster(this)">

                                            <div>
                                                <div class="text-[16px] font-semibold text-gray-900">
                                                    {{ $labelName }}
                                                </div>
                                                <div class="text-[13px] text-gray-500">
                                                    {{ $perms->count() }} permissions
                                                </div>
                                            </div>
                                        </label>

                                        <div class="flex flex-wrap gap-2">
                                            <button type="button"
                                                    onclick="toggleGroup('{{ $groupHash }}', true)"
                                                    class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-4 py-2 text-[14px] font-medium text-sky-700 transition hover:bg-sky-100">
                                                <i class="fa-solid fa-check mr-2"></i>
                                                Select Group
                                            </button>

                                            <button type="button"
                                                    onclick="toggleGroup('{{ $groupHash }}', false)"
                                                    class="inline-flex items-center rounded-[10px] border border-gray-300 bg-white px-4 py-2 text-[14px] font-medium text-gray-700 transition hover:bg-gray-50">
                                                <i class="fa-solid fa-xmark mr-2"></i>
                                                Clear Group
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permissions -->
                                <div class="px-4 py-4 md:px-6">
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                                        @foreach($perms as $perm)
                                            <label class="permission-card block h-full rounded-[14px] border border-gray-200 bg-white p-4">
                                                <div class="flex items-start gap-3">
                                                    <input type="checkbox"
                                                           class="perm-checkbox group-{{ $groupHash }} mt-1 h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500"
                                                           name="permissions[]"
                                                           value="{{ $perm->name }}"
                                                           {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}
                                                           onchange="syncMaster('{{ $groupHash }}'); syncPermissionCard(this)">

                                                    <div class="min-w-0">
                                                        <div class="text-[15px] font-semibold text-gray-900 break-words">
                                                            {{ ucfirst(last(explode('.', $perm->name))) }}
                                                        </div>
                                                        <div class="mt-1 text-[13px] text-gray-500 break-words">
                                                            {{ $perm->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-200 px-4 py-4 text-right md:px-6">
                    <button type="submit"
                            class="inline-flex items-center rounded-[12px] bg-sky-600 px-5 py-3 text-[15px] font-semibold text-white shadow-sm transition hover:bg-sky-700">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateAllPermissionCards() {
        document.querySelectorAll('.perm-checkbox').forEach((cb) => {
            syncPermissionCard(cb);
        });
    }

    function syncPermissionCard(checkbox) {
        const card = checkbox.closest('.permission-card');
        if (!card) return;

        if (checkbox.checked) {
            card.classList.add('checked');
        } else {
            card.classList.remove('checked');
        }
    }

    function toggleAll(state) {
        document.querySelectorAll('.perm-checkbox').forEach(cb => {
            cb.checked = state;
            syncPermissionCard(cb);
        });

        document.querySelectorAll('.group-master').forEach(cb => cb.checked = state);
    }

    function toggleGroup(hash, state) {
        document.querySelectorAll('.group-' + hash).forEach(cb => {
            cb.checked = state;
            syncPermissionCard(cb);
        });

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

        updateAllPermissionCards();
    });
</script>
@endsection