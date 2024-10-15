<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product_data = Products::where('is_active', 1)->get();

        if (!$product_data){
            return response()->json(false);
        }

        return response()->json($product_data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try { 
            $store = Products::create([
                'category_id' => $request['productCategory'],
                'brand_id' => $request['productBrand'],
                'price' => $request['productPrice'],
                'name' => $request['productName'],
                'specification' => $request['productSpecification'],
            ]);
            if(!$store){
                return response() -> json(false);
            }
            return response()->json(true);
        } catch (Exception $th) {
            return response() -> json(false);
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
