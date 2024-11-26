<?php

namespace App\Http\Controllers;

use App\Models\SupplierUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $suppliers = SupplierUser::all();
            return response()->json([
                'suppliers' => $suppliers,
                'message' => "Successfully fetched",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error fetching",
                'Error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'company' => 'required|string',
                'contact_person' => 'required|string',
                'contact_number' => 'required|string',
                'email_address' => 'required|email',
                'address' => 'required|string',
                'products' => 'required|string'
            ]);

            $data = SupplierUser::create($validatedData);
            return response()->json([
                "message" => "Successfully created a supplier user",
                "Success" => true,
                "data" => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Error creating supplier user",
                'Error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Attempt to fetch the supplier by ID
        $supplierUser = SupplierUser::find($id);

        // If no supplier is found, return an appropriate error
        if (!$supplierUser) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        // If found, return the supplier data
        return response()->json($supplierUser);
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $supplier = SupplierUSer::findOrFail($id);
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
    public function update(Request $request, SupplierUser $supplierUser)
    {
        try {
            // Log the request data (be cautious with sensitive data)
            Log::info('Incoming request data for update:', $request->all());

            // Validate the request data
            $validatedData = $request->validate([
                'company' => 'required|string',
                'contact_person' => 'required|string',
                'contact_number' => 'required|string',
                'email_address' => 'required|email',
                'address' => 'required|string',
                'products' => 'required|string',
            ]);

            // Check if the supplier exists before attempting to update
            if (!$supplierUser) {
                return response()->json([
                    'message' => 'Supplier not found',
                ], 404);
            }

            // Perform the update
            $supplierUser->update($validatedData);

            // Optionally reload the supplier from the database to ensure you return the latest data
            $supplierUser->refresh();

            // Return success response
            return response()->json([
                'message' => 'Supplier updated successfully',
                'data' => $supplierUser,
            ], 200);
        } catch (\Exception $e) {
            // Log the error and return failure response
            Log::error('Error updating supplier:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error updating supplier',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $supplier = SupplierUser::findOrFail($id);
            $supplier->delete();
            return response()->json([
                'message' => 'Successfully deleted supplier user',
                'Success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Error deleting supplier user",
                "Error" => $e->getMessage()
            ], 500);
        }
    }
}
