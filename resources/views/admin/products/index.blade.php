@extends('admin.master')

@section('content')
<style>
    .products-page { font-size: 16px !important; }
    .products-page .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
</style>

<div class="products-page min-h-screen bg-gray-100 py-8">
    <div class="w-full px-4 md:px-6 lg:px-8">

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-[30px] font-bold leading-tight text-gray-900">Products</h1>
                <p class="mt-1 text-[15px] text-gray-500">Manage your product catalog</p>
            </div>

            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center rounded-[12px] bg-sky-600 px-5 py-3 text-[15px] font-semibold text-white shadow-sm transition hover:bg-sky-700">
                <i class="fa-solid fa-plus mr-2"></i>
                New Product
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-[16px] border border-green-200 bg-green-50 px-4 py-4 shadow-sm text-[15px] text-green-800">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-[20px] border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-4 py-4 md:px-6">
                <form method="GET" class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center">
                        <div class="relative w-full md:w-[350px]">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
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
                            Total: {{ $products->total() }}
                        </span>
                    </div>
                </form>
            </div>

            <div class="table-scroll">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr class="border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">Product</th>
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">Shop</th>
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">Category</th>
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">Type</th>
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">Price</th>
                            <th class="px-6 py-4 text-left text-[14px] font-semibold uppercase tracking-wide text-gray-600">Status</th>
                            <th class="px-6 py-4 text-right text-[14px] font-semibold uppercase tracking-wide text-gray-600">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @forelse($products as $product)
                            @php
                                $primaryImage = $product->media->firstWhere('is_primary', true) ?? $product->media->first();
                            @endphp
                            <tr class="border-b border-gray-100 transition hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($primaryImage)
                                            <img src="{{ asset('storage/' . $primaryImage->file_path) }}"
                                                 alt="{{ $product->name }}"
                                                 class="h-12 w-12 rounded-lg border border-gray-200 object-cover">
                                        @else
                                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="text-[15px] font-semibold text-gray-900">{{ $product->name }}</div>
                                            <div class="mt-1 text-[13px] text-gray-500">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-[14px] text-gray-700">{{ $product->shop?->shop_name ?? '—' }}</td>
                                <td class="px-6 py-4 text-[14px] text-gray-700">{{ $product->category?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-[14px] text-gray-700">{{ ucfirst($product->product_type) }}</td>
                                <td class="px-6 py-4 text-[14px] text-gray-700">
                                    ৳{{ number_format($product->base_price, 2) }}
                                    @if($product->sale_price)
                                        <div class="text-[13px] text-red-500">Sale: ৳{{ number_format($product->sale_price, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-sky-100 px-3 py-1 text-[13px] font-medium text-sky-700">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-4 py-2 text-[14px] font-medium text-sky-700 transition hover:bg-sky-100">
                                            <i class="fa-solid fa-pen-to-square mr-2"></i>Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                              onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-[10px] border border-red-200 bg-red-50 px-4 py-2 text-[14px] font-medium text-red-700 transition hover:bg-red-100">
                                                <i class="fa-solid fa-trash mr-2"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-[15px] text-gray-500">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-4 py-4 md:px-6">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection