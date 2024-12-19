<?php

namespace App\Http\Controllers;

use App\Models\OrderDocument;
use Exception;
use Illuminate\Http\Request;

class OrderDocumentController extends Controller
{
    public function showOrderDocument($supplierId = 'null', $departmentId = 'null')
    {
        try {
            $query = OrderDocument::with(['supplier', 'order_document_type'])
                ->where('is_active', true);
            if ($supplierId != 'null') {
                $query->where('supplier_id', $supplierId);
            } else {
                $query->whereHas('supplier', function ($query2) use ($departmentId) {
                    $query2->whereHas('department_supplier', function ($query3) use ($departmentId) {
                        $query3->where('department_id', $departmentId);
                    });
                });
            }
            $documentData = $query->get();

            if (!$documentData) {
                return response()->json([
                    'response' => false,
                    'message' => 'No data found.'
                ]);
            }

            return response()->json($documentData);
        } catch (Exception $e) {
            return response()->json([
                'response' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
