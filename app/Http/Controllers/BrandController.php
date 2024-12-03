<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand_data = Brand::where('is_active', 1)->get();
        if (!$brand_data){
            return response()->json(false);
        }

        return response()->json($brand_data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request with custom error messages
            $request->validate([
                'brandName' => 'required|string|max:255|unique:brands,name',
            ], [
                'brandName.required' => 'The brand name field is required.',
                'brandName.string' => 'The brand name must be a valid string.',
                'brandName.max' => 'The brand name may not be greater than 255 characters.',
                'brandName.unique' => '" '. $request['brandName'] .' " has already been taken.',
            ]);
        
            // Create the brand
            $Brand = Brand::create([
                'name' => $request['brandName'],
            ]);
        
            // Check if the creation was successful
            if (!$Brand) {
                return response()->json(['success' => false, 'message' => 'Failed to create the brand.']);
            }
        
            return response()->json(['success' => true, 'message' => 'Brand created successfully.']);
        
        } catch (ValidationException $e) {
            // Return validation error messages
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        
        } catch (Exception $e) {
            // Return a general error response
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.']);
        }        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

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
        $validatedData = $request->validate([
            'brandName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->ignore((int)$id),
            ],
        ], [
            'brandName.unique' => 'The specified " '. $request['brandName'] .' " name is already in use.',
        ]);

        try {
            $brand = Brand::findOrFail($id);
            $brand->update([
                'name' => $request->input('brandName'),
            ]);

            return response()->json(true);
        } catch (Exception $e) {
            return response()->json(false, 400);
        }
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->update(['is_active' => 0]);

            return response()->json(['message' => 'Brand deactivated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Brand not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to deactivate brand'], 400);
        }
    }
}
