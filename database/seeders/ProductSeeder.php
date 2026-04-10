<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductMedia;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\ProductWarranty;
use App\Models\Shop;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::query()->first();
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();

        if (!$shop || $categories->isEmpty() || $brands->isEmpty() || $units->isEmpty()) {
            return;
        }

        $attributes = Attribute::with('values')->get();
        $variationAttributes = Attribute::where('is_variation', true)
            ->with('values')
            ->get()
            ->take(2);

        for ($i = 1; $i <= 20; $i++) {
            $productType = fake()->randomElement(['single', 'variant']);

            $sellerPrice = fake()->randomFloat(2, 200, 1500);
            $markupRate = fake()->randomElement([5, 10, 15, 20, 25, 30, 40]);
            $markupAmount = round(($sellerPrice * $markupRate) / 100, 2);
            $salePrice = round($sellerPrice + $markupAmount, 2);

            $product = Product::create([
                'shop_id'           => $shop->id,
                'category_id'       => $categories->random()->id,
                'brand_id'          => $brands->random()->id,
                'unit_id'           => $units->random()->id,
                'name'              => 'Product ' . $i,
                'slug'              => Str::slug('Product ' . $i) . '-' . $i,
                'sku'               => 'SKU-' . str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'barcode'           => fake()->ean13(),
                'product_type'      => $productType,
                'description'       => fake()->paragraph(),
                'specification'     => fake()->paragraph(),

                'purchase_price'    => fake()->randomFloat(2, 100, max(101, $sellerPrice - 20)),
                'seller_price'      => $sellerPrice,
                'sale_price'        => $salePrice,
                'markup_rate'       => $markupRate,
                'markup_amount'     => $markupAmount,
                'commission_rate'   => fake()->boolean(35) ? fake()->randomElement([5, 8, 10, 12, 15]) : null,

                // old fields optionally keep in sync if still present in table
                'regular_price'     => $salePrice,
                'discounted_price'  => null,

                'is_retail'         => true,
                'is_wholesale'      => fake()->boolean(40),
                'wholesale_price'   => fake()->boolean(40) ? fake()->randomFloat(2, 100, $sellerPrice) : null,
                'wholesale_min_qty' => fake()->boolean(40) ? fake()->numberBetween(5, 20) : null,
                'stock_qty'         => fake()->numberBetween(0, 100),
                'weight_kg'         => fake()->randomFloat(3, 0.1, 10),
                'warranty_type'     => fake()->randomElement(['no_warranty', 'seller_warranty']),
                'warranty_policy'   => fake()->boolean(50) ? fake()->sentence() : null,
                'tax_amount'        => fake()->randomFloat(2, 0, 15),
                'tax_type'          => fake()->randomElement(['fixed', 'percent']),
                'status'            => 'active',
                'is_featured'       => fake()->boolean(20),
                'is_price_approved' => true,
                'view_count'        => fake()->numberBetween(0, 5000),
                'rating_avg'        => fake()->randomFloat(2, 1, 5),
                'rating_count'      => fake()->numberBetween(0, 500),
                'published_at'      => now(),
            ]);

            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'role' => 'thumbnail',
                'file_path' => fake()->imageUrl(800, 800, 'products'),
                'sort_order' => 0,
                'is_primary' => true,
            ]);

            ProductMedia::factory()->count(2)->create([
                'product_id' => $product->id,
                'type' => 'image',
                'role' => 'gallery',
                'is_primary' => false,
            ]);

            if ($product->warranty_type === 'seller_warranty') {
                ProductWarranty::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'type' => 'service',
                    ],
                    [
                        'duration' => 12,
                        'duration_unit' => 'month',
                    ]
                );
            }

            foreach ($attributes->take(2) as $attribute) {
                $attributeValue = $attribute->values->isNotEmpty()
                    ? $attribute->values->random()
                    : null;

                ProductAttribute::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'attribute_value_id' => $attributeValue?->id,
                    'value_text' => $attributeValue ? null : fake()->word(),
                ]);
            }

            if ($productType === 'variant') {
                for ($v = 1; $v <= 3; $v++) {
                    $variantSellerPrice = fake()->randomFloat(2, 150, 1400);
                    $variantMarkupRate = fake()->randomElement([5, 10, 15, 20, 25, 30]);
                    $variantMarkupAmount = round(($variantSellerPrice * $variantMarkupRate) / 100, 2);
                    $variantSalePrice = round($variantSellerPrice + $variantMarkupAmount, 2);

                    $variant = ProductVariant::create([
                        'product_id'        => $product->id,
                        'name'              => 'Variant ' . $v,
                        'slug'              => Str::slug($product->name . ' Variant ' . $v),
                        'sku'               => $product->sku . '-V' . $v,
                        'barcode'           => fake()->ean13(),

                        'purchase_price'    => fake()->randomFloat(2, 100, max(101, $variantSellerPrice - 10)),

                        // keep variant prices aligned with new model if these columns exist in your variants table
                        'seller_price'      => $variantSellerPrice,
                        'sale_price'        => $variantSalePrice,
                        'markup_rate'       => $variantMarkupRate,
                        'markup_amount'     => $variantMarkupAmount,

                        // old fields fallback
                        'regular_price'     => $variantSalePrice,
                        'discounted_price'  => null,

                        'wholesale_price'   => fake()->boolean(40) ? fake()->randomFloat(2, 100, $variantSellerPrice) : null,
                        'wholesale_min_qty' => fake()->boolean(40) ? fake()->numberBetween(5, 20) : null,
                        'stock_qty'         => fake()->numberBetween(0, 100),
                        'weight_kg'         => fake()->randomFloat(3, 0.1, 10),
                        'image'             => fake()->imageUrl(600, 600, 'variants'),
                        'is_default'        => $v === 1,
                        'is_active'         => true,
                    ]);

                    foreach ($variationAttributes as $variationAttribute) {
                        if ($variationAttribute->values->isEmpty()) {
                            continue;
                        }

                        ProductVariantValue::firstOrCreate([
                            'product_variant_id' => $variant->id,
                            'attribute_id'       => $variationAttribute->id,
                            'attribute_value_id' => $variationAttribute->values->random()->id,
                        ]);
                    }
                }
            }
        }
    }
}