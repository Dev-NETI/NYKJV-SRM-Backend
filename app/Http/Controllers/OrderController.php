<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
