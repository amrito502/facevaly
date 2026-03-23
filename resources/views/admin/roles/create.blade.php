@extends('admin.master')

@section('content')
<div class="container-fluid py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">

                {{-- Page Header --}}
                <div class="mb-4">
                    <h1 class="h3 mb-1 text-dark fw-bold">Create Role</h1>
                    <p class="mb-0 text-muted">Add a new role for your system</p>
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

                {{-- Form Card --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
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
</div>
@endsection
