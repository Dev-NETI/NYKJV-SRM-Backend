<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category_data = Category::where('is_active', 1)->get();

        if (!$category_data){
            return response()->json(false);
        }

        return response()->json($category_data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request with custom error messages
            $request->validate([
                'categoryName' => 'required|string|max:255|unique:categories,name',
            ], [
                'categoryName.required' => 'The category name field is required.',
                'categoryName.string' => 'The category name must be a valid string.',
                'categoryName.max' => 'The category name may not be greater than 255 characters.',
                'categoryName.unique' => '" '. $request['categoryName'] .' " has already been taken.',
            ]);
        
            // Create the category
            $category = Category::create([
                'name' => $request['categoryName'],
            ]);
        
            // Check if the creation was successful
            if (!$category) {
                return response()->json(['success' => false, 'message' => 'Failed to create the category.']);
            }
        
            return response()->json(['success' => true, 'message' => 'Category created successfully.']);
        
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
            'categoryName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore((int)$id),
            ],
        ], [
            'categoryName.unique' => 'The specified " '. $request['categoryName'] .' " name is already in use.',
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->update([
                'name' => $request->input('categoryName'),
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
            $category = Category::findOrFail($id);
            $category->update(['is_active' => 0]);

            return response()->json(['message' => 'Category deactivated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to deactivate category'], 400);
        }
    }
}
