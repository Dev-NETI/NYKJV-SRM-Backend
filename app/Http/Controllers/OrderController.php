<?php

namespace App\Http\Controllers;

use App\Mail\OrdersMailer;
use App\Models\Order;
use App\Models\OrderDocument;
use App\Models\TempQuotation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sendQuotation(Request $request)
    {
        $request->validate([
            'fileQuotation' => 'required|file|mimes:pdf|max:2048',
            'emails' => 'required',
            'supplierId' => 'required',
            'orderDocumentTypeId' => 'required',
        ]);

        try {
            $quotation = $request->file('fileQuotation');

            $storeQuotation = $quotation->storeAs('public/order-document', $quotation->hashName());

            if (!$storeQuotation) {
                return response()->json([
                    'response' => false,
                    'message' => 'Whoops! Something went wrong!',
                    'severity' => 'error'
                ], 400);
            }

            $store = OrderDocument::create([
                'supplier_id' => $request['supplierId'],
                'order_document_type_id' => $request['orderDocumentTypeId'],
                'file_name' => $request['fileName'],
                'file_path' => $quotation->hashName()
            ]);

            if (!$store) {
                return response()->json([
                    'response' => false,
                    'message' => 'Whoops! Something went wrong!',
                    'severity' => 'error'
                ], 400);
            }

            $quotationUrl = env('APP_URL') . "/storage/order-document/" . $quotation->hashName();
            Mail::to($request['emails'])->send(new OrdersMailer($request['company'], $quotationUrl, $request['emailBody']));

            return response()->json([
                'response' => true,
                'message' => 'Quotation sent successfully!',
                'severity' => 'success'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'response' => false,
                'message' => $e->getMessage(),
                'severity' => 'error'
            ], 500);
        }
    }

    public function index()
    {
        //
    }

    public function showOrderByStatus($orderStatusId = 1, $supplierId = 14)
    {
        try {
            $orders = Order::where('is_active', true)
                ->where('order_status_id', $orderStatusId)
                ->where('supplier_id', $supplierId)
                ->get();

            $orderData = $orders->groupBy('reference_number')->map(function ($group) {
                return [
                    'reference_number' => $group->first()->reference_number,
                    'orders' => $group->map(function ($order) {
                        return [
                            'id' => $order->id,
                            'product_id' => $order->product_id,
                            'quantity' => $order->quantity,
                            'order_status_id' => $order->order_status_id,
                            'supplier_id' => $order->supplier_id,
                            'is_active' => $order->is_active,
                            'created_by' => $order->created_by,
                            'modified_by' => $order->modified_by,
                            'created_at' => $order->created_at,
                            'updated_at' => $order->updated_at,
                        ];
                    })
                ];
            })->values();

            return response()->json(['orders' => $orderData]);
        } catch (ModelNotFoundException $e) {
            return response()->json(false);
        } catch (QueryException $e) {
            return response()->json(false);
        } catch (Exception $e) {
            return response()->json(false);
        }
    }

    public function showOrderItems($referenceNumber)
    {
        try {
            $orderItems = Order::with(['product', 'product.brand'])
                ->where('is_active', true)
                ->where('reference_number', $referenceNumber)
                ->get();

            return response()->json($orderItems);
        } catch (ModelNotFoundException $e) {
            return response()->json(false);
        } catch (QueryException $e) {
            return response()->json(false);
        } catch (Exception $e) {
            return response()->json(false);
        }
    }

    public function updateOrderStatus($referenceNumber, $newOrderStatus)
    {
        try {
            $updatedRows = Order::where('reference_number', $referenceNumber)
                ->update(['order_status_id' => $newOrderStatus]);

            if ($updatedRows === 0) {
                return response()->json(false);
            }

            return response()->json(true);
        } catch (QueryException $e) {
            return response()->json(false, 400);
        } catch (Exception $e) {
            return response()->json(false, 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
