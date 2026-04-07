<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'product_type' => ['required', Rule::in(['single', 'variant'])],

            'description' => ['required', 'string'],
            'specification' => ['nullable', 'string'],

            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'regular_price' => ['nullable', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'min:0'],

            'is_retail' => ['nullable', 'boolean'],
            'is_wholesale' => ['nullable', 'boolean'],
            'wholesale_price' => ['nullable', 'numeric', 'min:0'],
            'wholesale_min_qty' => ['nullable', 'integer', 'min:1'],

            'stock_qty' => ['nullable', 'integer', 'min:0'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],

            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'unit_id' => ['nullable', 'exists:units,id'],

            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'barcode' => ['nullable', 'string', 'max:255'],

            'warranty_type' => ['required', Rule::in(['no_warranty', 'seller_warranty'])],
            'warranty_policy' => ['nullable', 'string'],

            'replacement_warranty_enabled' => ['nullable', 'boolean'],
            'replacement_duration' => ['nullable', 'integer', 'min:1'],
            'replacement_duration_unit' => ['nullable', Rule::in(['day', 'month', 'year'])],

            'service_warranty_enabled' => ['nullable', 'boolean'],
            'service_duration' => ['nullable', 'integer', 'min:1'],
            'service_duration_unit' => ['nullable', Rule::in(['day', 'month', 'year'])],

            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];
    }
}