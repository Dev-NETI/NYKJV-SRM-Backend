<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('is_active', 1)
            ->where('f_name', 'like', '%' . $request->f_name . '%')
            ->where('l_name', 'like', '%' . $request->l_name . '%');

        if ($request->company_info) {
            $users = $users->with('company');
        }

        if ($request->department_info) {
            $users = $users->with('department');
        }

        if ($request->supplier_info) {
            $users = $users->with('supplier');
        }

        $users = $users->paginate($request->limit ?? 10);

        if (!$users) {
            return response()->json(['error' => 'No users found'], 404);
        }

        return response()->json($users);
    }

    public function show($slug)
    {
        $userData = User::where('slug', $slug)->first();
        if (!$userData) {
            return response()->json(false);
        }
        return response()->json($userData);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'nullable|string|max:255',
            'm_name' => 'nullable|string|max:255',
            'l_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'company_id' => 'nullable',
            'department_id' => 'nullable',
            'supplier_id' => 'nullable',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $store = User::create([
                'f_name' => $request['f_name'],
                'm_name' => $request['m_name'],
                'l_name' => $request['l_name'],
                'suffix' => $request['suffix'],
                'contact_number' => $request['contact_number'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'company_id' => $request['company_id'],
                'department_id' => $request['department_id'],
                'supplier_id' => $request['supplier_id'],
            ]);

            if (!$store) {
                return response()->json(['message' => 'Failed to create user'], 500);
            }

            return response()->json(['message' => 'User created successfully', 'user' => $store], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->first();
        if (!$user) {
            return response()->json(false);
        }
        $user->update($request->all());
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 201);
    }
}
