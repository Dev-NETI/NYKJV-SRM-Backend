<?php

namespace App\Http\Controllers;

use App\Models\SupplierDocument;
use App\Models\DocumentType;
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
        ]);

        try {
            $document = $request->file('fileDocument');
            $storeDocument = $document->storeAs('public/supplier-documents', $document->hashName());

            if (!$storeDocument) {
                return response()->json([
                    'response' => false,
                    'message' => 'Whoops! Something went wrong!'
                ], 400);
            }

            $store = SupplierDocument::create([
                'supplier_id' => $request['supplierId'],
                'document_type_id' => $request['documentTypeId'],
                'name' => $request['fileName'],
                'file_path' => $document->hashName(),
                'expired_at' => $request['expiration'],
            ]);

            if (!$store) {
                return response()->json([
                    'response' => false,
                    'message' => 'Whoops! Something went wrong!'
                ], 400);
            }

            return response()->json([
                'response' => true,
                'message' => 'Document uploaded successfully!'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'response' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showDocuments($supplierId, $isActive = 1)
    {
        try {
            $documentData = SupplierDocument::where('supplier_id', $supplierId)
                ->where('is_active', $isActive)
                ->orderBy('created_at', 'desc')
                ->get();

            if (!$documentData) {
                return response()->json(false);
            }

            return response()->json($documentData);
        } catch (Exception $e) {
            return response()->json(false);
        }
    }

    public function showDocumentsByCategory($supplierId, $categoryId, $isActive = 1)
    {
        try {
            $documents = SupplierDocument::with(['document_type' => function ($query) use ($categoryId, $isActive) {
                $query->where('document_type_category_id', $categoryId);
            }])
                ->where('supplier_id', $supplierId)
                ->where('is_active', $isActive)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($documents);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public function showMissingDocuments($supplierId, $categoryId)
    {
        try {
            $allDocumentTypes = DocumentType::where('document_type_category_id', $categoryId)->pluck('id');

            $uploadedDocumentTypes = SupplierDocument::where('supplier_id', $supplierId)
                ->where('is_active', 1)
                ->whereHas('document_type', function ($query) use ($categoryId) {
                    $query->where('document_type_category_id', $categoryId);
                })
                ->pluck('document_type_id');

            $missingDocumentTypes = $allDocumentTypes->diff($uploadedDocumentTypes);

            $missingDocuments = DocumentType::whereIn('id', $missingDocumentTypes)->get();

            return response()->json($missingDocuments);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function hasDocument($supplierId, $documentTypeId)
    {
        try {
            $document = SupplierDocument::where('document_type_id', $documentTypeId)
                ->where('is_active', 1)
                ->exists();
            return response()->json($document);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
            return response()->json(false, 404);
        } catch (QueryException $e) {

            return response()->json(false, 500);
        } catch (Exception $e) {

            return response()->json(false, 500);
        }
    }

    public function recycleDocument($id)
    {
        try {
            $documentData = SupplierDocument::where('id', $id)
                ->firstOrFail();

            $documentData->update([
                'is_active' => 1
            ]);

            return response()->json(true, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(false, 404);
        } catch (QueryException $e) {

            return response()->json(false, 500);
        } catch (Exception $e) {

            return response()->json(false, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $documentData = SupplierDocument::where('id', $id)
                ->firstOrFail();

            $documentData->delete();

            return response()->json(true, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(false, 404);
        } catch (QueryException $e) {

            return response()->json(false, 500);
        } catch (Exception $e) {

            return response()->json(false, 500);
        }
    }
}
