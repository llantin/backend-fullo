<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoleModule;
use Illuminate\Http\Request;

class RoleModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleModules = RoleModule::all();
        return response()->json([
            'status' => true,
            'roleModules' => $roleModules
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
