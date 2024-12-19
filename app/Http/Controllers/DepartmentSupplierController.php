<?php

namespace App\Http\Controllers;

use App\Models\DepartmentSupplier;
use Exception;
use Illuminate\Http\Request;

class DepartmentSupplierController extends Controller
{
    public function showSupplierPerDepartment($departmentId)
    {
        try {
            $supplierData = DepartmentSupplier::with(['supplier'])
                ->where('department_id', $departmentId)
                ->where('is_active', 1)
                ->get();

            if (!$supplierData) {
                return response()->json([
                    'response' => false,
                    'message' => 'No data found!',
                ]);
            }

            return response()->json($supplierData);
        } catch (Exception $e) {
            return response()->json([
                'response' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
