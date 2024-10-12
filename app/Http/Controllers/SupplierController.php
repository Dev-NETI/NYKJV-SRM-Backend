<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $suppliers = Supplier::all();
            return response()->json([
                'suppliers' => $suppliers,
                'message' => 'Suppliers fetched successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching suppliers: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch suppliers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Incoming request data:', $request->all());

            // Validate incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'island_id' => 'required|integer',
                'province_id' => 'required|integer',
                'municipality_id' => 'required|integer',
                'brgy_id' => 'required|integer',
                'street_address' => 'required|string|max:255',
            ]);

            $validatedData['modified_by'] = $request->user()->name;

            // Create a new supplier
            $supplier = Supplier::create($validatedData);

            return response()->json([
                'message' => 'Supplier Created Successfully',
                'supplier' => $supplier
            ], 201);
        } catch (ValidationException $e) {
            // Log validation errors
            Log::error('Validation failed while storing supplier: ' . json_encode($e->errors()));
            return response()->json([
                'message' => 'Validation error occurred',
                'errors' => $e->errors(), // Return validation errors
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing supplier data: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error sending data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return response()->json(['message' => 'Supplier not found'], 404);
            }

            return response()->json($supplier, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching supplier: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching supplier'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $supplier = Supplier::where('slug', $slug)->first();

        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            // Validate incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'island_id' => 'required|integer',
                'province_id' => 'required|integer',
                'municipality_id' => 'required|integer',
                'brgy_id' => 'required|integer',
                'street_address' => 'nullable|string|max:255',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation failed for supplier update: ' . json_encode($e->errors()));
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error fetching supplier for update: ' . $e->getMessage());
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        Log::info('Updating supplier', ['id' => $id, 'data' => $validatedData]);
        $supplier->update($validatedData);

        return response()->json(['message' => 'Supplier updated successfully.', 'supplier' => $supplier], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implement the destroy method if needed
    }
}