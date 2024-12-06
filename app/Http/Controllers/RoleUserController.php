<?php

namespace App\Http\Controllers;

use App\Models\RoleUser as ModelsRoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $store = ModelsRoleUser::create([
            'user_id' => $request->user_id,
            'role_id' => $request->role_id,
        ]);

        if (!$store) {
            return response()->json(['message' => 'Failed to create role user'], 500);
        }

        return response()->json(['message' => 'Role user created successfully', 'role_user' => $store], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
    public function destroy($id)
    {
        $roleUser = ModelsRoleUser::findOrFail($id);

        if (!$roleUser->delete()) {
            return response()->json(['message' => 'Failed to delete role user'], 500);
        }

        return response()->json(['message' => 'Role user deleted successfully'], 200);
    }

    public function currentUserRoles(Request $request)
    {
        $roleUser = ModelsRoleUser::where('user_id', $request->id)->get();

        return response()->json($roleUser);
    }
}
