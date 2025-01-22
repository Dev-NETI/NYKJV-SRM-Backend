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

    public function getProduct($supplierId = 'null')
    {
        try {
            if ($supplierId === 'null') {
                $query = Products::with(['category', 'brand'])->where('is_active', 1);
            } else {
                $query = Products::with(['category', 'brand'])->where('is_active', 1)->where('supplier_id', $supplierId);
            }
            $productData = $query->get();

            return response()->json($productData, 200);
        } catch (Exception $e) {
            return response()->json([
                'response' => false,
                'message' => $e->getMessage()
            ], 422);
        }
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
<<<<<<< HEAD
            // Handle the image upload
            $productImage = $request->file('productImage');
            
            // Store the image and get the hash name
            $imagePath = $productImage->storeAs('public/product-image', $productImage->hashName());

            if (!$imagePath) {
                return response()->json(['success' => false, 'message' => 'Failed to upload the product image.'], 500);
            }
=======
            // Validate the request with custom error messages
            $request->validate([
                'productName' => 'required|string|max:255|unique:products,name',
                'productPrice' => 'required|numeric|min:1',
                'productCategory' => 'required',
                'productBrand' => 'required',
                'productSpecification' => 'required|string|max:255',
                'fileImage' => 'required|file|image|max:2048',
                'currencyId' => 'required'
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
>>>>>>> 83c9d469118b9ba162d5f2a6829abb9251de363f

            $image = $request->file('fileImage');
            $storeImage = $image->storeAs('public/products', $image->hashName());

            if (!$storeImage) {
                return response()->json(['response' => false, 'message' => 'Failed to save product image!.']);
            }

            // Create the product
            $product = Products::create([
                'product_image' => $productImage->hashName(), // Use hash name for the image
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'currency' => $request['productCurrency'],
                'price' => $request['productPrice'],
                'price_vat_ex' => $request['productPriceVatEx'],
                'name' => $request['productName'],
                'specification' => $request['productSpecification'],
                'image_path' => $image->hashName(),
                'currency_id' => $request['currencyId'],
                'supplier_id' => $request['supplierId'],
            ]);

            // Check if the creation was successful
            if (!$product) {
<<<<<<< HEAD
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
=======
                return response()->json(['response' => false, 'message' => 'Failed to create the product!']);
            }

            return response()->json(['response' => true, 'message' => 'Product created successfully!']);
        } catch (ValidationException $e) {
            // Return validation error messages
            return response()->json(['response' => false, 'message' => $e->getMessage()], 422);
        } catch (Exception $e) {
            // Return a general error response
            return response()->json(['response' => false, 'message' => $e->getMessage()]);
>>>>>>> 83c9d469118b9ba162d5f2a6829abb9251de363f
        }
    }

    public function patchProduct(Request $request)
    {
        try {
            $product = Products::where('id', $request['productId'])->first();

            $updateData = [
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'price' => $request['productPrice'],
                'price_vat_ex' => $request['productPriceVatEx'],
                'name' => $request['productName'],
                'specification' => $request['productSpecification'],
                'currency_id' => (int)$request['currencyId'],
            ];

            if ($request->file('fileImage')) {
                $image = $request->file('fileImage');
                $updateImage = $image->storeAs('public/products', $image->hashName());

                if (!$updateImage) {
                    return response()->json(['response' => false, 'message' => 'Failed to update product image!.']);
                }

                $updateData['image_path'] = $image->hashName();
            }

            $update = $product->update($updateData);

            if (!$update) {
                return response()->json(['response' => false, 'message' => 'Failed to update the product!'], 400);
            }

            return response()->json(['response' => true, 'message' => 'Product updated successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['response' => false, 'message' => $e->getMessage()], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = Products::findOrFail($id);

            return response()->json($product, 200);
        } catch (Exception $e) {
            return response()->json([
                'response' => false,
                'message' => $e->getMessage()
            ], 404);
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
<<<<<<< HEAD
    public function update(Request $request, $id)
    {
        // Find the product
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }

        // Validate the request
        $request->validate([
            'productImage' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'productCurrency' => 'required|string',
            'productName' => 'required|string|max:255|unique:products,name,' . $id,
            'productPrice' => 'required|numeric|min:1',
            'productCategory' => 'required|exists:categories,id',
            'productBrand' => 'required|exists:brands,id',
            'productSpecification' => 'required|string|max:255',
        ], [
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
            // Handle the image upload if a new image is provided
            if ($request->hasFile('productImage')) {
                $productImage = $request->file('productImage');

                // Store the image and get the hash name
                $imagePath = $productImage->storeAs('public/product-image', $productImage->hashName());

                if (!$imagePath) {
                    return response()->json(['success' => false, 'message' => 'Failed to upload the product image.'], 500);
                }

                // Delete the old image if it exists
                if ($product->product_image) {
                    Storage::delete('public/product-image/' . $product->product_image);
                }

                // Update the product image field
                $product->product_image = $productImage->hashName();
            }

            // Update other product details
            $product->category_id = $request['productCategory'];
            $product->brand_id = $request['productBrand'];
            $product->currency = $request['productCurrency'];
            $product->price = $request['productPrice'];
            $product->name = $request['productName'];
            $product->specification = $request['productSpecification'];

            // Save the updated product
            if (!$product->save()) {
                return response()->json(['success' => false, 'message' => 'Failed to update the product.'], 500);
            }

            // Return successful response
            return response()->json(['success' => true, 'message' => 'Product updated successfully.', 'product' => $product], 200);
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
=======

>>>>>>> 83c9d469118b9ba162d5f2a6829abb9251de363f

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
