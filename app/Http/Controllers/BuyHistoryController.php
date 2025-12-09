<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;

class BuyHistoryController extends Controller
{
    /**
     * Display the user's order history
     */
    public function index(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::guard('userclient')->check()) {
            return redirect()->route('client.login.form')->with('error', 'Please login to view your order history.');
        }

        $user = Auth::guard('userclient')->user();
        $perPage = 10; // Number of orders per page

        // Get orders with their items, paginated
        $orders = Order::with(['orderItems'])
            ->where('user_id', $user->id)
            ->withCount('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Transform orders for frontend
        $transformedOrders = $orders->getCollection()->map(function ($order) {
            return [
                'id' => $order->id,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'full_name' => $order->full_name,
                'contact' => $order->contact,
                'order_date' => $order->order_date,
                'total_amount' => number_format($order->total_amount, 2),
                'status' => $order->status,
                'created_at' => $order->created_at,
                'order_items_count' => $order->order_items_count,
                'order_items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'subtotal' => $item->subtotal,
                        'metric' => $item->metric
                    ];
                })
            ];
        });

        // Check if this is an AJAX request (for modal loading)
        if ($request->expectsJson() || $request->ajax() || $request->header('X-Inertia')) {
            return response()->json([
                'orders' => $transformedOrders,
                'hasMoreOrders' => $orders->hasMorePages(),
                'currentPage' => $orders->currentPage(),
                'totalOrders' => $orders->total()
            ]);
        }

        // Return Inertia view for full page load
        return Inertia::render('Client/buyHistory', [
            'orders' => $transformedOrders,
            'hasMoreOrders' => $orders->hasMorePages(),
            'currentPage' => $orders->currentPage(),
            'totalOrders' => $orders->total()
        ]);
    }

    /**
     * Get order details for a specific order
     */
    public function show($orderId)
    {
        if (!Auth::guard('userclient')->check()) {
            return redirect()->route('client.login.form')->with('error', 'Please login to view order details.');
        }

        $user = Auth::guard('userclient')->user();

        $order = Order::with(['orderItems'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            return redirect()->route('buy.history')->with('error', 'Order not found.');
        }

        return Inertia::render('Client/orderDetails', [
            'order' => [
                'id' => $order->id,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'full_name' => $order->full_name,
                'contact' => $order->contact,
                'order_date' => $order->order_date,
                'total_amount' => number_format($order->total_amount, 2),
                'status' => $order->status,
                'created_at' => $order->created_at,
                'order_items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'subtotal' => $item->subtotal,
                        'metric' => $item->metric
                    ];
                })
            ]
        ]);
    }

    /**
     * Get order statistics for user dashboard
     */
    public function getOrderStats()
    {
        if (!Auth::guard('userclient')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('userclient')->user();

        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'Pending'])
                ->count(),
            'completed_orders' => Order::where('user_id', $user->id)
                ->whereIn('status', ['completed', 'Completed'])
                ->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->whereNotIn('status', ['cancelled', 'Cancelled'])
                ->sum('total_amount'),
            'recent_orders' => Order::where('user_id', $user->id)
                ->with(['orderItems'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'total_amount' => number_format($order->total_amount, 2),
                        'status' => $order->status,
                        'created_at' => $order->created_at,
                        'items_count' => $order->orderItems->count()
                    ];
                })
        ];

        return response()->json($stats);
    }
}
