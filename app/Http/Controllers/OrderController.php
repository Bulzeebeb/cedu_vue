<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Mark order as paid
     */
    public function markAsPaid(Order $order)
    {
        try {
            // Change status to 'Completed' (with capital C) to match frontend expectations
            $order->update(['status' => 'Completed']);

            return redirect()->back()->with('success', 'Order marked as paid successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }

    /**
     * Delete order and all related order items
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                // Delete all order items first (due to foreign key constraints)
                OrderItem::where('order_id', $order->id)->delete();

                // Then delete the order
                $order->delete();
            });

            return redirect()->back()->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete order.');
        }
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $order->load('orderItems');

        return response()->json([
            'order' => $order,
            'items' => $order->orderItems
        ]);
    }

    /**
     * Get all orders for admin
     */
    public function index()
    {
        $orders = Order::with('orderItems')->latest()->paginate(20);

        return inertia('Admin/Orders', [
            'orders' => $orders
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Cancelled,pending,paid,cancelled,completed'
        ]);

        try {
            $order->update(['status' => $request->status]);

            return redirect()->back()->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }
}
