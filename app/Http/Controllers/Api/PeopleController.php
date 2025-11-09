<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Person::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $people = $query->get();

        return response()->json([
            'status' => true,
            'people' => $people
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $person = Person::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Person created successfully!",
            'person' => $person
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $person->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Person updated successfully!",
            'person' => $person
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json([
            'status' => true,
            'message' => 'Person deleted successfully!'
        ], 200);
    }
}
