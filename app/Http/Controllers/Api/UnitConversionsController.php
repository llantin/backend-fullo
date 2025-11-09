<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitConversion;

class UnitConversionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit_conversions = UnitConversion::all();
        return response()->json([
            'status' => true,
            'unit_conversions' => $unit_conversions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $unit_conversion = UnitConversion::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit conversion created successfully!",
            'unit_conversion' => $unit_conversion
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitConversion $unit_conversion)
    {
        $unit_conversion->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit conversion updated successfully!",
            'unit_conversion' => $unit_conversion
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitConversion $unit_conversion)
    {
        $unit_conversion->delete();
        return response()->json([
            'status' => true,
            'message' => "Unit conversion deleted successfully!"
        ], 200);
    }

    public function getUnits($base_unit)
    {
        $units = UnitConversion::where('base_unit', $base_unit)->get();
        return response()->json([
            'status' => true,
            'units' => $units
        ]);
    }
}
