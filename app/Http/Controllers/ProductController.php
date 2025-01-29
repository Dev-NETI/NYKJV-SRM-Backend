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
                $query = Products::with(['category', 'brand', 'supplier'])->where('is_active', 1)->orderBy('name', 'asc');
            } else {
                $query = Products::with(['category', 'brand', 'supplier'])->where('is_active', 1)->where('supplier_id', $supplierId)->orderBy('name', 'asc');
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
        try {
            // Validate the request with custom error messages
            $request->validate([
                'productName' => 'required|string|max:255|unique:products,name',
                'productPrice' => 'required|numeric',
                'productCategory' => 'required',
                'productBrand' => 'required',
                'productSpecification' => 'required|string|max:255',
                'currencyId' => 'required'
            ], [
                'productName.required' => 'The product name field is required.',
                'productName.string' => 'The product name must be a valid string.',
                'productName.max' => 'The product name may not be greater than 255 characters.',
                'productName.unique' => '" ' . $request['productName'] . ' " has already been taken.',
                'productPrice.required' => 'The product price is required.',
                'productPrice.numeric' => 'The product price must be a number.',
                'productCategory.required' => 'The product category is required.',
                'productBrand.required' => 'The product brand is required.',
                'productSpecification.required' => 'The product specification is required.',
            ]);

            $storeData = [
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'price' => $request['productPrice'],
                'price_vat_ex' => $request['productPriceVatEx'],
                'name' => $request['productName'],
                'specification' => $request['productSpecification'],
                'currency_id' => $request['currencyId'],
                'supplier_id' => $request['supplierId'],
            ];

            if ($request->file('fileImage')) {
                $image = $request->file('fileImage');
                $storeImage = $image->storeAs('public/products', $image->hashName());

                if (!$storeImage) {
                    return response()->json(['response' => false, 'message' => 'Failed to save product image!.']);
                }
                $storeData['image_path'] = $image->hashName();
            }

            // Create the product
            $product = Products::create($storeData);

            // Check if the creation was successful
            if (!$product) {
                return response()->json(['response' => false, 'message' => 'Failed to create the product!']);
            }

            return response()->json(['response' => true, 'message' => 'Product created successfully!']);
        } catch (ValidationException $e) {
            // Return validation error messages
            return response()->json(['response' => false, 'message' => $e->getMessage()], 422);
        } catch (Exception $e) {
            // Return a general error response
            return response()->json(['response' => false, 'message' => $e->getMessage()]);
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
