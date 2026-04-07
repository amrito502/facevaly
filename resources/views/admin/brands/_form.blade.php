@props([
    'brand' => null,
    'submitText' => 'Save',
])

<div class="space-y-6">

    <div>
        <label for="name" class="mb-2 block text-[15px] font-semibold text-gray-900">
            Brand Name
        </label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $brand?->name) }}"
            placeholder="e.g. Apple"
            class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none transition placeholder:text-gray-400
            @error('name')
                border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100
            @else
                border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200
            @enderror"
        >
        @error('name')
            <p class="mt-2 text-[14px] font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="mb-2 block text-[15px] font-semibold text-gray-900">
            Slug
        </label>
        <input
            type="text"
            id="slug"
            name="slug"
            value="{{ old('slug', $brand?->slug) }}"
            placeholder="e.g. apple"
            class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none transition placeholder:text-gray-400
            @error('slug')
                border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100
            @else
                border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200
            @enderror"
        >
        @error('slug')
            <p class="mt-2 text-[14px] font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="logo" class="mb-2 block text-[15px] font-semibold text-gray-900">
            Brand Logo
        </label>
        <input
            type="file"
            id="logo"
            name="logo"
            class="block w-full rounded-[12px] border border-gray-300 bg-white px-4 py-3 text-[15px] text-gray-700"
        >

        @if($brand?->logo)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $brand->logo) }}"
                     alt="{{ $brand->name }}"
                     class="h-20 rounded-lg border border-gray-200 object-cover">
            </div>
        @endif

        @error('logo')
            <p class="mt-2 text-[14px] font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-[15px] font-semibold text-gray-900">
            Description
        </label>
        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Write brand description..."
            class="w-full rounded-[12px] border bg-white px-4 py-3 text-[15px] text-gray-700 outline-none transition placeholder:text-gray-400
            @error('description')
                border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100
            @else
                border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200
            @enderror"
        >{{ old('description', $brand?->description) }}</textarea>

        @error('description')
            <p class="mt-2 text-[14px] font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <input
            type="checkbox"
            id="is_active"
            name="is_active"
            value="1"
            @checked(old('is_active', $brand?->is_active ?? true))
            class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500"
        >
        <label for="is_active" class="text-[15px] font-medium text-gray-700">
            Active Brand
        </label>
    </div>

    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.brands.index') }}"
           class="inline-flex h-[46px] items-center justify-center rounded-[12px] border border-gray-300 bg-white px-5 text-[15px] font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Cancel
        </a>

        <button type="submit"
                class="inline-flex h-[46px] items-center justify-center rounded-[12px] bg-sky-600 px-5 text-[15px] font-semibold text-white shadow-sm transition hover:bg-sky-700">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            {{ $submitText }}
        </button>
    </div>
</div>