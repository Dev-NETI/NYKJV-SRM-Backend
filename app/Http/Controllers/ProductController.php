<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        // Validate the request
        $request->validate([
            'productImage' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'productCurrency' => 'required|string',
            'productName' => 'required|string|max:255|unique:products,name',
            'productPrice' => 'required|numeric|min:1',
            'productCategory' => 'required|exists:categories,id',
            'productBrand' => 'required|exists:brands,id',
            'productSpecification' => 'required|string|max:255',
        ], [
            'productImage.required' => 'Image is required.',
            'productImage.image' => 'Invalid image format.',
            'productImage.mimes' => 'Allowed formats: jpeg, png, jpg, svg.',
            'productImage.max' => 'Image size must not exceed 2MB.',
            'productName.required' => 'Name is required.',
            'productName.unique' => '"' . $request['productName'] . '" has already been taken.',
            'productPrice.required' => 'Price is required.',
            'productPrice.numeric' => 'Price must be a number.',
            'productPrice.min' => 'Price must be at least 1.',
            'productCategory.required' => 'Category is required.',
            'productCategory.exists' => 'Invalid category.',
            'productBrand.required' => 'Brand is required.',
            'productBrand.exists' => 'Invalid brand.',
            'productSpecification.required' => 'Specification is required.',
        ]);

        try {
            // Handle the image upload
            $productImage = $request->file('productImage');
            
            // Store the image and get the hash name
            $imagePath = $productImage->storeAs('public/product-image', $productImage->hashName());

            if (!$imagePath) {
                return response()->json(['success' => false, 'message' => 'Failed to upload the product image.'], 500);
            }

            // Create the product
            $product = Products::create([
                'product_image' => $productImage->hashName(), // Use hash name for the image
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'currency' => $request['productCurrency'],
                'price' => $request['productPrice'],
                'name' => $request['productName'],
                'specification' => $request['productSpecification'],
            ]);

            // Check if the creation was successful
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Failed to create the product.'], 500);
            }

            // Return successful response
            return response()->json(['success' => true, 'message' => 'Product created successfully.', 'product' => $product], 201);
        } catch (ValidationException $e) {
            // Log validation errors
            Log::error('Validation Error: ', $e->errors());
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Log other errors for debugging
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
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
        // Validate the request
        $request->validate([
            // 'productImage' => 'required|image|mimes:jpeg,png,jpg,svg',
            'productName' => 'required|string|max:255|unique:products,name,' . $id, // Ignore the current product
            'productCurrency' => 'required|string',
            'productPrice' => 'required|numeric|min:1',
            'productCategory' => 'required|exists:categories,id',
            'productBrand' => 'required|exists:brands,id',
            'productSpecification' => 'required|string|max:255',
        ], [
            // 'productImage.required' => '"' . $id . '" Image is required.',
            // 'productImage.image' => 'Invalid image format.',
            // 'productImage.mimes' => 'Allowed formats: jpeg, png, jpg, svg.',
            // 'productImage.max' => 'Image size must not exceed 2MB.',
            'productName.required' => 'Name is required.',
            'productName.unique' => '"' . $request['productName'] . '" has already been taken.',
            'productPrice.required' => 'Price is required.',
            'productPrice.numeric' => 'Price must be a number.',
            'productPrice.min' => 'Price must be at least 1.',
            'productCategory.required' => 'Category is required.',
            'productCategory.exists' => 'Invalid category.',
            'productBrand.required' => 'Brand is required.',
            'productBrand.exists' => 'Invalid brand.',
            'productSpecification.required' => 'Specification is required.',
        ]);

        try {
            // Find the product
            $product = Products::findOrFail($id);
            
            // Handle image upload if provided
            if ($request->hasFile('productImage')) {
                $productImage = $request->file('productImage');
                $imagePath = $productImage->storeAs('public/product-image', $productImage->hashName());

                if (!$imagePath) {
                    return response()->json(['success' => false, 'message' => 'Failed to upload the product image.'], 500);
                }

                // Update the image field
                $product->product_image = $productImage->hashName();
            }

            // Update the product details
            $product->update([
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'product_image' => $productImage->hashName(), // Use hash name for the image
                'name' => $request['productName'],
                'currency' => $request['productCurrency'],
                'price' => $request['productPrice'],
                'specification' => $request['productSpecification'],
            ]);

            // Return successful response
            return response()->json(['success' => true, 'message' => 'Product updated successfully.', 'product' => $product], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        } catch (ValidationException $e) {
            // Log validation errors
            Log::error('Validation Error: ', $e->errors());
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Log other errors for debugging
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
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
}
