<?php

namespace App\Http\Controllers\Seller;

use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
        ]);

        $unit = Unit::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::lower(Str::random(4)),
            'short_name' => Str::lower(substr($validated['name'], 0, 4)),
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Unit created successfully.',
            'unit' => [
                'id' => $unit->id,
                'name' => $unit->name,
                'short_name' => $unit->short_name,
            ],
        ]);
    }
}