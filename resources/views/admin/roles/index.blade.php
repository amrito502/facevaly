@extends('admin.master')

@section('content')
<style>
    .roles-page {
        font-size: 16px !important;
    }

    .roles-page table {
        width: 100%;
        border-collapse: collapse;
    }

    .roles-page .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

<div class="roles-page min-h-screen bg-gray-100 py-8">
    <div class="w-full px-4 md:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-[30px] font-bold leading-tight text-gray-900">
                    Roles
                </h1>
                <p class="mt-1 text-[15px] text-gray-500">
                    Manage roles and assign permissions
                </p>
            </div>

            <div>
                <a href="{{ route('roles.create') }}"
                   class="inline-flex items-center rounded-[12px] bg-sky-600 px-5 py-3 text-[15px] font-semibold text-white shadow-sm transition hover:bg-sky-700">
                    <i class="fa-solid fa-plus mr-2"></i>
                    New Role
                </a>
            </div>
        </div>

        <!-- Success Message -->
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

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-5 rounded-[16px] border border-red-200 bg-red-50 px-4 py-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="pt-0.5 text-red-600">
                        <i class="fa-solid fa-triangle-exclamation text-[18px]"></i>
                    </div>
                    <div class="text-[15px] text-red-800">
                        <strong class="mb-2 block font-semibold">
                            Please fix the following:
                        </strong>
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

            <!-- Search Area -->
            <div class="border-b border-gray-200 px-4 py-4 md:px-6">
                <form method="GET" class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="relative w-full md:w-[350px]">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text"
                                   name="q"
                                   value="{{ request('q') }}"
                                   placeholder="Search roles..."
                                   class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white pl-11 pr-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                        </div>

                        <button type="submit"
                                class="inline-flex h-[48px] items-center justify-center rounded-[12px] border border-gray-300 bg-white px-5 text-[15px] font-medium text-gray-700 transition hover:bg-gray-50">
                            <i class="fa-solid fa-search mr-2"></i>
                            Search
                        </button>
                    </div>

                    <div>
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-4 py-2 text-[14px] font-semibold text-sky-700">
                            Total: {{ $roles->total() }}
                        </span>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-scroll">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">
                                Name
                            </th>
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">
                                Permissions
                            </th>
                            <th class="px-6 py-4 text-right text-[14px] font-semibold uppercase tracking-wide text-gray-600">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @forelse($roles as $role)
                            <tr class="border-b border-gray-100 align-top transition hover:bg-gray-50">
                                <!-- Role Name -->
                                <td class="px-6 py-4">
                                    <div class="text-[15px] font-semibold text-gray-900">
                                        {{ $role->name }}
                                    </div>
                                    <div class="mt-1 text-[14px] text-gray-500">
                                        <i class="fa-solid fa-hashtag mr-1"></i>
                                        ID: {{ $role->id }}
                                    </div>
                                </td>

                                <!-- Permissions -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($role->permissions->take(6) as $perm)
                                            <span class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-3 py-2 text-[13px] font-medium text-gray-700">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach

                                        @if($role->permissions->count() > 6)
                                            <span class="inline-flex items-center px-1 text-[13px] text-gray-500">
                                                +{{ $role->permissions->count() - 6 }} more
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a href="{{ route('roles.permissions.edit', $role) }}"
                                           class="inline-flex items-center rounded-[10px] border border-cyan-200 bg-cyan-50 px-4 py-2 text-[14px] font-medium text-cyan-700 transition hover:bg-cyan-100">
                                            <i class="fa-solid fa-key mr-2"></i>
                                            Assign Permissions
                                        </a>

                                        <a href="{{ route('roles.edit', $role) }}"
                                           class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-4 py-2 text-[14px] font-medium text-sky-700 transition hover:bg-sky-100">
                                            <i class="fa-solid fa-pen-to-square mr-2"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('roles.destroy', $role) }}"
                                              onsubmit="return confirm('Delete this role?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center rounded-[10px] border border-red-200 bg-red-50 px-4 py-2 text-[14px] font-medium text-red-700 transition hover:bg-red-100">
                                                <i class="fa-solid fa-trash mr-2"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-[15px] text-gray-500">
                                    <i class="fa-regular fa-folder-open mr-2"></i>
                                    No roles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="border-t border-gray-200 px-4 py-4 md:px-6">
                {{ $roles->withQueryString()->links() }}
            </div>
        </div>

    </div>
</div>
@endsection