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
    public function index(Request $request)
    {
        // Retrieve active categories
        $category_data = Category::where('is_active', 1);

        // Apply department filter if provided
        if ($request->department_id) {
            $category_data = $category_data->where('department_id', $request->department_id);
        }

        // Fetch the data
        $category_data = $category_data->get();

        // Check if any categories were found
        if ($category_data->isEmpty()) {
            return response()->json(false);
        }

        // Return response with or without department_id
        if ($request->has('department_id')) {
            return response()->json([
                'category_data' => $category_data,
                'department_id' => $request->department_id,
            ]);
        } else {
            return response()->json($category_data);
        }
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
                'departmentId' => 'required|int',
            ], [
                'categoryName.required' => 'The category name field is required.',
                'categoryName.string' => 'The category name must be a valid string.',
                'categoryName.max' => 'The category name may not be greater than 255 characters.',
                'categoryName.unique' => 'The category name "' . $request['categoryName'] . '" has already been taken.',
                'departmentId.required' => 'The department ID is required.',
                'departmentId.int' => 'The department ID must be a valid number.',
            ]);
        
            // Create the category
            $category = Category::create([
                'name' => $request['categoryName'],
                'department_id' => $request['departmentId'],
            ]);
        
            // Check if the creation was successful
            if (!$category) {
                return response()->json(['success' => false, 'message' => 'Failed to create the category.'], 500);
            }
        
            return response()->json(['success' => true, 'message' => 'Category created successfully.']);
        
        } catch (ValidationException $e) {
            // Return validation error messages
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        
        } catch (Exception $e) {
            // Log the exception for debugging
            \Log::error($e->getMessage());
            
            // Return a general error response
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
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
