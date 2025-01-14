<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::where('is_active', 1)->with(['category', 'brand'])->get();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request with custom error messages
            $request->validate([
                'productName' => 'required|string|max:255|unique:products,name',
                'productPrice' => 'required|numeric|min:1',
                'productCategory' => 'required',
                'productBrand' => 'required',
                'productSpecification' => 'required|string|max:255',
            ], [
                'productName.required' => 'The product name field is required.',
                'productName.string' => 'The product name must be a valid string.',
                'productName.max' => 'The product name may not be greater than 255 characters.',
                'productName.unique' => '" ' . $request['productName'] . ' " has already been taken.',
                'productPrice.required' => 'The product price is required.',
                'productPrice.numeric' => 'The product price must be a number.',
                'productPrice.min' => 'The product price must be at least 1.',
                'productCategory.required' => 'The product category is required.',
                'productBrand.required' => 'The product brand is required.',
                'productSpecification.required' => 'The product specification is required.',
            ]);

            // Create the product
            $product = Products::create([
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'price' => $request['productPrice'],
                'name' => $request['productName'],
                'specification' => $request['productSpecification'],
            ]);

            // Check if the creation was successful
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Failed to create the product.']);
            }

            return response()->json(['success' => true, 'message' => 'Product created successfully.']);
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
        try {
            $product = Products::findOrFail($id);

            return response()->json($product);
        } catch (Exception $e) {
            return response()->json(false, 404); // Return 404 if product not found
        }
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $product = Products::findOrFail($id);

            $product->update([
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'price' => $request['productPrice'],
                'name' => $request['productName'],
                'specification' => $request['productSpecification'],
            ]);

            return response()->json(true);
        } catch (Exception $e) {
            return response()->json(false, 400);
        }
    }

    public function updatePrice($productId, $newPrice)
    {
        $validator = Validator::make(
            ['price' => $newPrice],
            ['price' => 'required|numeric|min:0']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid price. Please enter a valid positive number.'
            ], 422);
        }

        try {
            $product = Products::findOrFail($productId);

            $product->update([
                'price' => $newPrice
            ]);

            return response()->json(true);
        } catch (ModelNotFoundException $e) {
            return response()->json(false, 400);
        } catch (QueryException $e) {
            return response()->json(false, 400);
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
            $product = Products::findOrFail($id);

            $product->update([
                'is_active' => 0,
            ]);

            return response()->json(true);
        } catch (Exception $e) {
            return response()->json(false, 400);
        }
    }

    public function total_count()
    {
        try {
            Log::info('Starting total_count method');
            $total = Products::where('is_active', 1)->count();
            Log::info('Active products count: ' . $total);
            return response()->json([
                'total' => $total,
                'message' => 'Successfully counted active products',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in total_count: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error counting products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
