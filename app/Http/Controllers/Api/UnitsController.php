<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::all();
        return response()->json([
            'status' => true,
            'units' => $units
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $unit = Unit::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit created successfully!",
            'unit' => $unit
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $unit->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit updated successfully!",
            'unit' => $unit
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->json([
            'status' => true,
            'message' => "Unit deleted successfully!"
        ], 200);
    }
}
