<?php

namespace App\Http\Controllers;

use App\Models\SupplierDocument;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SupplierDocumentController extends Controller
{
    public function store(Request $request)
    {
            $request->validate([
                'supplierId' => 'required',
                'documentTypeId' => 'required',
                'fileName' => 'required',
                'filePath' => 'required',
            ]);

            try {
                $store = SupplierDocument::create([
                    'supplier_id' => $request['supplierId'],
                    'document_type_id' => $request['documentTypeId'],
                    'name' => $request['fileName'],
                    'file_path' => $request['filePath'],
                ]);

                if(!$store){
                    return response()->json(false);
                }

                return response()->json(true);
            } catch (Exception $e) {
                return response()->json(false);
            }
    }

    public function show($supplierId)
    {
        try {
            $documentData = SupplierDocument::where('supplier_id', $supplierId)
                        ->where('is_active',1)
                        ->orderBy('created_at','desc')
                        ->get();

            if(!$documentData){
                return response()->json(false);
            }

            return response()->json($documentData);
        } catch (Exception $e) {
            return response()->json(false);
        }

    }
    
    public function moveToTrash($id)
    {
        try {
            $documentData = SupplierDocument::where('id', $id)
                            ->firstOrFail();
                            
            $documentData->update([
                'is_active' => 0
            ]);
            
            return response()->json(true, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json($e->getMessage(), 404);

        } catch (QueryException $e) {
            
            return response()->json($e->getMessage(), 500);

        } catch (Exception $e) {
            
            return response()->json($e->getMessage(), 500);
        }
    }

}
