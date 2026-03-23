@extends('admin.master')

@section('content')
<div class="container-fluid py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">

                {{-- Header --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1 text-dark fw-bold">Edit User</h1>
                        <p class="mb-0 text-muted">Update user info and role</p>
                    </div>

                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back
                    </a>
                </div>

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

                {{-- Card --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                {{-- Name --}}
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label fw-semibold text-dark">Name</label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Enter name"
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label fw-semibold text-dark">Email</label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="Enter email"
                                           class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Role --}}
                                <div class="col-12">
                                    <label for="role" class="form-label fw-semibold text-dark">Role</label>
                                    @php
                                        $currentRoleName = optional($user->roles->first())->name;
                                    @endphp
                                    <select name="role"
                                            id="role"
                                            class="form-select @error('role') is-invalid @enderror">
                                        <option value="">Select role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ old('role', $currentRoleName) == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Current: {{ $currentRoleName ?? 'No role' }}
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="col-12">
                                    <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2 pt-2">
                                        <a href="{{ route('users.index') }}"
                                           class="btn btn-outline-secondary px-4">
                                            <i class="fa-solid fa-xmark me-2"></i>Cancel
                                        </a>

                                        <button type="submit"
                                                class="btn btn-info text-white px-4">
                                            <i class="fa-solid fa-floppy-disk me-2"></i>Update User
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
