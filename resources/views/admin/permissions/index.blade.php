@extends('admin.master')

@section('content')
<style>
    /* Fix for admin template shrinking Tailwind UI */
    .permissions-page {
        font-size: 16px !important;
    }

    .permissions-page table {
        width: 100%;
        border-collapse: collapse;
    }

    .permissions-page th,
    .permissions-page td {
        vertical-align: middle;
    }

    .permissions-page .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>

<div class="permissions-page min-h-screen bg-gray-100 py-8">
    <div class="w-full px-4 md:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-[30px] font-bold text-gray-900 leading-tight">
                    Permissions
                </h1>
                <p class="mt-1 text-[15px] text-gray-500">
                    Create and manage permissions
                </p>
            </div>

            <div>
                <a href="{{ route('permissions.create') }}"
                   class="inline-flex items-center rounded-[12px] bg-sky-600 px-5 py-3 text-[15px] font-semibold text-white shadow hover:bg-sky-700 transition">
                    <i class="fa-solid fa-plus mr-2"></i>
                    New Permission
                </a>
            </div>
        </div>

        <!-- Card -->
        <div class="rounded-[18px] border border-gray-200 bg-white shadow-sm">

            <!-- Search -->
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
                                   placeholder="Search permissions..."
                                   class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white pl-11 pr-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                        </div>

                        <button type="submit"
                                class="inline-flex h-[48px] items-center justify-center rounded-[12px] border border-gray-300 bg-white px-5 text-[15px] font-medium text-gray-700 hover:bg-gray-50 transition">
                            <i class="fa-solid fa-search mr-2"></i>
                            Search
                        </button>
                    </div>

                    <div>
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-4 py-2 text-[14px] font-semibold text-sky-700">
                            Total: {{ $permissions->total() }}
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
                                ID
                            </th>
                            <th class="px-6 py-4 text-right text-[14px] font-semibold uppercase tracking-wide text-gray-600">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @forelse($permissions as $permission)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-[15px] font-semibold text-gray-900">
                                    {{ $permission->name }}
                                </td>

                                <td class="px-6 py-4 text-[14px] text-gray-500">
                                    #{{ $permission->id }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a href="{{ route('permissions.edit', $permission) }}"
                                           class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-4 py-2 text-[14px] font-medium text-sky-700 hover:bg-sky-100 transition">
                                            <i class="fa-solid fa-pen-to-square mr-2"></i>
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('permissions.destroy', $permission) }}"
                                              onsubmit="return confirm('Delete this permission?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center rounded-[10px] border border-red-200 bg-red-50 px-4 py-2 text-[14px] font-medium text-red-700 hover:bg-red-100 transition">
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
                                    No permissions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="border-t border-gray-200 px-4 py-4 md:px-6">
                {{ $permissions->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection