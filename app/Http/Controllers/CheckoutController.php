<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::guard('userclient')->check()) {
            return redirect()->route('client.login.form')->with('error', 'Please login to proceed to checkout.');
        }

        $cart = Session::get('cart', []);

        // Check if cart is empty
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Get authenticated user
        $user = Auth::guard('userclient')->user();

        // Calculate totals
        $orderItems = array_values($cart);
        $totalPrice = array_reduce($orderItems, function ($sum, $item) {
            return $sum + (floatval($item['price']) * $item['qty']);
        }, 0);

        return Inertia::render('OnlineMarket/checkoutPage', [
            'orderItems' => $orderItems,
            'totalPrice' => number_format($totalPrice, 2),
            'user' => $user,
            'cartCount' => array_sum(array_column($orderItems, 'qty'))
        ]);
    }

    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::guard('userclient')->check()) {
            $message = 'Please login to place an order.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 401);
            }
            return redirect()->route('client.login.form')->with('error', $message);
        }

        // Validate billing information
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'date' => 'required|date',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            $message = 'Your cart is empty.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 400);
            }
            return redirect()->route('cart.index')->with('error', $message);
        }

        $user = Auth::guard('userclient')->user();

        // Calculate total
        $totalPrice = array_reduce($cart, function ($sum, $item) {
            return $sum + (floatval($item['price']) * $item['qty']);
        }, 0);

        try {
            // Create order record
            $order = Order::create([
                'user_id' => $user->id,
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'contact' => $request->contact,
                'order_date' => $request->date,
                'total_amount' => $totalPrice,
                'status' => 'pending'
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['title'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => floatval($item['price']) * $item['qty'],
                    'metric' => $item['metric']
                ]);
            }

            // Prepare order data for POS display
            $orderData = [
                'orderId' => $order->id,
                'customerName' => $request->firstName . ' ' . $request->lastName,
                'contact' => $request->contact,
                'orderDate' => date('Y-m-d', strtotime($request->date)),
                'totalAmount' => number_format($totalPrice, 2),
                'items' => array_values($cart)
            ];

            // Clear the cart
            Session::forget('cart');

            // Store order data in session for POS display
            Session::put('pos_order_data', $orderData);

            // Return JSON for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'orderId' => $order->id,
                    'message' => 'Order placed successfully!'
                ]);
            }

            // Redirect for non-AJAX requests
            return redirect()->route('pos.display')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            $message = 'Failed to place order. Please try again.';
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 500);
            }
            return redirect()->back()->with('error', $message);
        }
    }

    public function displayPOS()
    {
        // Get order data from session
        $orderData = Session::get('pos_order_data');

        if (!$orderData) {
            return redirect()->route('client.landing')->with('error', 'No order data found.');
        }

        return Inertia::render('OnlineMarket/onlineMart_pos', [
            'orderData' => $orderData
        ]);
    }

    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'qty'));
    }
}
