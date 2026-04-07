@props([
    'category' => null,
    'parents' => [],
    'submitText' => 'Save',
])

<div class="space-y-6">

    <div>
        <label for="parent_id" class="mb-2 block text-[15px] font-semibold text-gray-900">Parent Category</label>
        <select
            id="parent_id"
            name="parent_id"
            class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200"
        >
            <option value="">No Parent</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $category?->parent_id) == $parent->id)>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="name" class="mb-2 block text-[15px] font-semibold text-gray-900">Category Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $category?->name) }}"
            placeholder="e.g. Electronics"
            class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none transition placeholder:text-gray-400 @error('name') border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100 @else border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 @enderror"
        >
        @error('name')
            <p class="mt-2 text-[14px] font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="slug" class="mb-2 block text-[15px] font-semibold text-gray-900">Slug</label>
        <input
            type="text"
            id="slug"
            name="slug"
            value="{{ old('slug', $category?->slug) }}"
            placeholder="e.g. electronics"
            class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none transition placeholder:text-gray-400 @error('slug') border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100 @else border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 @enderror"
        >
        @error('slug')
            <p class="mt-2 text-[14px] font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label for="icon" class="mb-2 block text-[15px] font-semibold text-gray-900">Icon</label>
            <input
                type="text"
                id="icon"
                name="icon"
                value="{{ old('icon', $category?->icon) }}"
                placeholder="fa-solid fa-tag"
                class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200"
            >
        </div>

        <div>
            <label for="sort_order" class="mb-2 block text-[15px] font-semibold text-gray-900">Sort Order</label>
            <input
                type="number"
                id="sort_order"
                name="sort_order"
                value="{{ old('sort_order', $category?->sort_order ?? 0) }}"
                min="0"
                class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200"
            >
        </div>
    </div>

    <div>
        <label for="image" class="mb-2 block text-[15px] font-semibold text-gray-900">Image</label>
        <input
            type="file"
            id="image"
            name="image"
            class="block w-full rounded-[12px] border border-gray-300 bg-white px-4 py-3 text-[15px] text-gray-700"
        >

        @if($category?->image)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-20 rounded-lg border border-gray-200 object-cover">
            </div>
        @endif

        @error('image')
            <p class="mt-2 text-[14px] font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3">
        <input
            type="checkbox"
            id="is_active"
            name="is_active"
            value="1"
            @checked(old('is_active', $category?->is_active ?? true))
            class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500"
        >
        <label for="is_active" class="text-[15px] font-medium text-gray-700">Active Category</label>
    </div>

    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.categories.index') }}"
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