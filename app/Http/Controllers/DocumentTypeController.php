<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Exception;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        try {
            $documentTypeData = DocumentType::where('is_active', 1)->orderBy('name', 'asc')->get();

            if (!$documentTypeData) {
                return response()->json(false);
            }

            return response()->json($documentTypeData);
        } catch (Exception $e) {
            return response()->json(false);
        }
    }
}
