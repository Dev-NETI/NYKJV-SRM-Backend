<?php

namespace App\Http\Controllers;

use App\Models\OrderDocumentType;
use Exception;
use Illuminate\Http\Request;

class OrderDocumentTypeController extends Controller
{
    public function index()
    {
        try {
            $orderDocumentType = OrderDocumentType::where('is_active', true)->get();

            if (!$orderDocumentType) {
                return response()->json([
                    'response' => false,
                    'message' => 'No data found.'
                ]);
            }

            return response()->json($orderDocumentType);
        } catch (Exception $e) {
            return response()->json([
                'response' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
