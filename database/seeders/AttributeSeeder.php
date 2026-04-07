<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Color',
                'type' => 'color',
                'is_filterable' => true,
                'is_variation' => true,
                'values' => ['Red', 'Blue', 'Black', 'White'],
            ],
            [
                'name' => 'Size',
                'type' => 'select',
                'is_filterable' => true,
                'is_variation' => true,
                'values' => ['S', 'M', 'L', 'XL'],
            ],
            [
                'name' => 'Storage',
                'type' => 'select',
                'is_filterable' => true,
                'is_variation' => true,
                'values' => ['64GB', '128GB', '256GB'],
            ],
            [
                'name' => 'Material',
                'type' => 'text',
                'is_filterable' => false,
                'is_variation' => false,
                'values' => ['Cotton', 'Leather', 'Plastic'],
            ],
        ];

        foreach ($attributes as $item) {
            $attribute = Attribute::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'type' => $item['type'],
                'is_filterable' => $item['is_filterable'],
                'is_variation' => $item['is_variation'],
                'is_required' => false,
                'is_active' => true,
            ]);

            foreach ($item['values'] as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                    'slug' => Str::slug($value),
                    'meta' => null,
                    'is_active' => true,
                ]);
            }
        }
    }
}