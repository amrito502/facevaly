@props([
    'permission' => null,
    'submitText' => 'Save',
])

<div class="space-y-6">

    <div>
        <label for="name" class="mb-2 block text-[15px] font-semibold text-gray-900">
            Permission Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $permission?->name) }}"
            required
            placeholder="e.g. edit posts"
            class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none transition placeholder:text-gray-400 focus:ring-2
            @error('name')
                border-red-300 focus:border-red-400 focus:ring-red-100
            @else
                border-gray-300 focus:border-sky-500 focus:ring-sky-200
            @enderror"
        >

        @error('name')
            <p class="mt-2 text-[14px] font-medium text-red-600">
                {{ $message }}
            </p>
        @enderror

        <p class="mt-2 text-[13px] text-gray-500">
            Example: create users, edit posts, delete roles
        </p>
    </div>

    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <a href="{{ route('permissions.index') }}"
           class="inline-flex h-[46px] items-center justify-center rounded-[12px] border border-gray-300 bg-white px-5 text-[15px] font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Cancel
        </a>

        <button
            type="submit"
            class="inline-flex h-[46px] items-center justify-center rounded-[12px] bg-sky-600 px-5 text-[15px] font-semibold text-white shadow-sm transition hover:bg-sky-700">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            {{ $submitText }}
        </button>
    </div>

</div>