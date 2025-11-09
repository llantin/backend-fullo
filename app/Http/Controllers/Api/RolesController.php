<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleModule;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('modules')->get();
        return response()->json([
            'status' => true,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);
        $role = Role::create($request->all());
        if ($request->has('modules') && is_array($request->modules)) {
            $role_modules = RoleModule::insert(
                array_map(function ($module) use ($role) {
                    return [
                        'role_id' => $role->id,
                        'module_id' => $module,
                    ];
                }, $request->modules)
            );
        }
        $role->load('modules');
        return response()->json([
            'status' => true,
            'message' => "Role created successfully!",
            'role' => $role
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'modules' => 'array|min:1',
        ]);

        $role->update($request->only(['name', 'description']));

        if ($request->has('modules') && is_array($request->modules)) {
            $role->modules()->sync($request->modules);
        }

        $role->load('modules');

        return response()->json([
            'status' => true,
            'message' => "Role updated successfully!",
            'role' => $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'status' => true,
            'message' => "Role deleted successfully!"
        ], 200);
    }
}
