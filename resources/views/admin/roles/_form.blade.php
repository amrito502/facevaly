<div class="row g-4">

    {{-- Role Name --}}
    <div class="col-12">
        <label for="name" class="form-label fw-semibold text-dark">
            Role Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $role?->name ?? '') }}"
            required
            placeholder="e.g. admin"
            class="form-control @error('name') is-invalid @enderror"
        >

        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="form-text">
            Use a simple, unique name.
        </div>
    </div>

    {{-- Actions --}}
    <div class="col-12">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2">
            <a href="{{ route('roles.index') }}"
               class="btn btn-outline-secondary px-4">
                <i class="fa-solid fa-arrow-left me-2"></i>Cancel
            </a>

            <button type="submit"
                    class="btn btn-info text-white px-4">
                <i class="fa-solid fa-floppy-disk me-2"></i>{{ $submitText ?? 'Save' }}
            </button>
        </div>
    </div>

</div>
