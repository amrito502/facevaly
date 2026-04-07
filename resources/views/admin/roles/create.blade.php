@extends('admin.master')

@section('content')
<style>
    .role-create-page {
        font-size: 16px !important;
    }
</style>

<div class="role-create-page min-h-screen bg-gray-100 py-8">
    <div class="w-full px-4 md:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-[30px] font-bold leading-tight text-gray-900">
                    Create Role
                </h1>
                <p class="mt-1 text-[15px] text-gray-500">
                    Add a new role for your system
                </p>
            </div>

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

            <!-- Form Card -->
            <div class="rounded-[20px] border border-gray-200 bg-white shadow-sm">
                <div class="px-5 py-5 md:px-8 md:py-8">
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        @include('admin.roles._form', [
                            'role' => null,
                            'submitText' => 'Create'
                        ])
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection