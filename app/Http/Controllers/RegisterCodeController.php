<?php

namespace App\Http\Controllers;

use App\Models\register_code;
use Illuminate\Http\Request;

class RegisterCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registerCodes = register_code::all();
        return response()->json($registerCodes);
    }

    public function checkCode(Request $request)
    {
        $registerCode = register_code::where('code', $request->code)->where('is_used', false)->first();
        if ($registerCode) {
            return response()->json(['message' => 'Code is valid']);
        } else {
            return response()->json(['message' => 'Code is invalid']);
        }
    }

    public function useCode(Request $request)
    {
        $registerCode = register_code::where('code', $request->code)->first();
        if ($registerCode) {
            $registerCode->email = $request->email;
            $registerCode->is_used = true;
            $registerCode->save();
            return response()->json(['message' => 'Code is used']);
        } else {
            return response()->json(['message' => 'Code is invalid']);
        }
    }

    public function generateCode(Request $request)
    {
        $registerCode = register_code::create([
            'code' => $request->code,
            'is_used' => false,
        ]);
        return response()->json($registerCode, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(register_code $register_code)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(register_code $register_code)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, register_code $register_code)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(register_code $register_code)
    {
        //
    }
}
