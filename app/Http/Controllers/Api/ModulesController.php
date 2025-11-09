<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modules = Module::all();
        return response()->json([
            'status' => true,
            'modules' => $modules
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $module = Module::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Module created successfully!",
            'module' => $module
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        $module->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Module updated successfully!",
            'module' => $module
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $module->delete();
        return response()->json([
            'status' => true,
            'message' => "Role deleted successfully!"
        ], 200);
    }
}
