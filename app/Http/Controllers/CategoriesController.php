<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
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
            $store = Category::create([
                'name' => $request['categoryName'],
            ]);
        
            // Check if the creation was successful
            if (!$store) {
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
        // Validate the request data with a custom message for the unique rule
        $validatedData = $request->validate([
            'categoryName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($id),
            ],
        ], [
            'categoryName.unique' => 'The specified category name is already in use.',
        ]);

        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            // Check if the category name has changed
            if ($category->name === $validatedData['categoryName']) {
                return response()->json([
                    'message' => 'No changes were made as the category name is the same.'
                ], 200);
            }

            // Check if the category is associated with other entities
            if ($category->products()->exists()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Category cannot be updated as it has associated products.'
                ], 422);
            }

            // Update the category name
            $category->update(['name' => $validatedData['categoryName']]);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the category.'
            ], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
