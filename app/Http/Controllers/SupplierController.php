<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
    
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        $suppliers = $query->paginate(10);
    
        return response()->json([
            'suppliers' => $suppliers->items(),
            'pagination' => [
                'current_page' => $suppliers->currentPage(),
                'total' => $suppliers->total(),
                'last_page' => $suppliers->lastPage(),
            ]
        ]);
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
                'island' => 'required|string',
                'region_id' => 'required|integer',
                'province_id' => 'nullable|integer',
                'district_id' => 'nullable|integer',
                'city_id' => 'nullable|integer',
                'municipality_id' => 'nullable|integer',
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
                'errors' => $e->errors(),
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
    public function edit(string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            return response()->json([
                'supplier' => $supplier,
                'message' => 'Successfully fetched supplier',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching supplier' . $e->getMessage());
            return response()->json([
                'message' => 'Supplier ID not found',
                'error' => $e->getMessage()
            ], 500);
        }
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
                'island' => 'required|string',
                'region_id' => 'required|integer',
                'province_id' => 'nullable|integer',
                'district_id' => 'nullable|integer',
                'city_id' => 'nullable|integer',
                'municipality_id' => 'nullable|integer',
                'brgy_id' => 'required|integer',
                'street_address' => 'required|string|max:255',
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
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            return response()->json([
                'message' => 'Successfully deleted supplier',
                'supplier' => $supplier
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting supplier' . $e->getMessage());
            return response()->json([
                'message' => 'Supplier not found',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function fetch_update(string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            return response()->json([
                'supplier' => $supplier,
                'message' => 'Successfully fetched supplier',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching supplier' . $e->getMessage());
            return response()->json([
                'message' => 'Supplier ID not found',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            // Get search query parameters
            $name = $request->input('name');
            $perPage = $request->input('per_page', 10);  // Allow users to specify per page
    
            // Validate the name parameter (if provided)
            if (!is_string($name) || empty($name)) {
                return response()->json([
                    'message' => 'Invalid or missing search term',
                    'suppliers' => [],
                ], 400);  // Return bad request if the name is not a valid string
            }
    
            // Sanitize the search term to escape special characters for LIKE query
            $sanitizedSearchTerm = addcslashes($name, '%_'); // Escape % and _
    
            // Search for suppliers with the sanitized name using a "like" query
            $suppliers = Supplier::where('name', 'like', '%' . $sanitizedSearchTerm . '%')
                ->paginate($perPage);
    
            return response()->json([
                'message' => 'Search results fetched successfully',
                'suppliers' => $suppliers->items(),
                'pagination' => [
                    'current_page' => $suppliers->currentPage(),
                    'last_page' => $suppliers->lastPage(),
                    'total' => $suppliers->total(),
                    'per_page' => $suppliers->perPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error searching suppliers: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error searching suppliers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
