<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role', 'person')->get();
        return response()->json([
            'status' => true,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        $user->load(['person', 'role']);
        return response()->json([
            'status' => true,
            'message' => "User created successfully!",
            'user' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        $user->load(['person', 'role']);
        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
            'user' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully!'
        ], 200);
    }
}
