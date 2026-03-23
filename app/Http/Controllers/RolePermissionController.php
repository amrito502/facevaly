<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionController extends Controller
{
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();

        // checked permissions as names
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        $groupedPermissions = $permissions->groupBy(function ($perm) {
            $name = trim($perm->name);

            // dot style: users.view
            if (str_contains($name, '.')) {
                $group = trim(explode('.', $name)[0] ?? '');
                $group = $group !== '' ? $group : 'General';
                return ucfirst(str_replace(['_', '-'], ' ', $group));
            }

            // space style: "create user", "view blog post"
            $parts = preg_split('/\s+/', $name);

            if (count($parts) >= 2) {
                // group = everything after first word
                $group = trim(implode(' ', array_slice($parts, 1)));
                return $group !== '' ? ucfirst($group) : 'General';
            }

            return 'General';
        });

        // optional: sort group names (User আগে রাখতে চাইলে custom sort করতে পারেন)
        $groupedPermissions = $groupedPermissions->sortKeys();

        return view('admin.roles.assign-permissions', compact('role', 'rolePermissions', 'groupedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Permissions assigned to role!');
    }
}
