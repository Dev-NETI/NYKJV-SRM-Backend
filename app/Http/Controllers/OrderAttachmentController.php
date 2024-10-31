<?php

namespace App\Http\Controllers;

use App\Models\OrderAttachment;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OrderAttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'referenceNumber' => 'required',
            'name' => 'required',
            'filePath' => 'required',
        ]);
        try {
            OrderAttachment::create([
                'reference_number' => $request['referenceNumber'],
                'name' => $request['name'],
                'file_path' => $request['filePath'],
            ]);

            return response()->json(true);
        } catch (ModelNotFoundException $e) {
            return response()->json(false, 400);
        } catch (QueryException $e) {
            return response()->json(false, 400);
        } catch (Exception $e) {
            return response()->json(false, 400);
        }
    }
}
