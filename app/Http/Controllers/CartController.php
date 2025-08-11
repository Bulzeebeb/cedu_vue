<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);

        return Inertia::render('OnlineMarket/addtocartPage', [
            'cart' => array_values($cart)
        ]);
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        // Check if product has stock
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Product is out of stock!');
        }

        // Get current cart from session
        $cart = Session::get('cart', []);

        // Check if product already exists in cart
        if (isset($cart[$productId])) {
            // Check if adding one more exceeds available stock
            if ($cart[$productId]['qty'] >= $product->stock) {
                return redirect()->back()->with('error', 'Cannot add more items. Stock limit reached!');
            }
            $cart[$productId]['qty'] += 1;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'title' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'metric' => $product->unit,
                'image' => $product->image,
                'stock' => $product->stock
            ];
        }

        // REAL-TIME STOCK UPDATE: Decrease stock in database
        $product->decrement('stock', 1);

        // Update product status if stock becomes zero
        if ($product->stock <= 0) {
            $product->update(['status' => 'Out of Stock']);
        }

        // Store updated cart in session
        Session::put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $newQuantity = max(1, intval($request->input('quantity')));

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $oldQuantity = $cart[$productId]['qty'];
            $difference = $newQuantity - $oldQuantity;

            $product = Product::find($productId);
            if ($product) {
                // Check if we have enough stock for the increase
                if ($difference > 0 && $product->stock < $difference) {
                    return redirect()->back()->with('error', 'Not enough stock available!');
                }

                // Update database stock
                if ($difference > 0) {
                    $product->decrement('stock', $difference);
                } else if ($difference < 0) {
                    $product->increment('stock', abs($difference));
                }

                // Update product status
                if ($product->stock <= 0) {
                    $product->update(['status' => 'Out of Stock']);
                } else {
                    $product->update(['status' => 'Available']);
                }
            }

            $cart[$productId]['qty'] = $newQuantity;
            Session::put('cart', $cart);
        }

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $quantity = $cart[$productId]['qty'];

            // RESTORE STOCK: Add back the quantity to database
            $product = Product::find($productId);
            if ($product) {
                $product->increment('stock', $quantity);
                if ($product->stock > 0) {
                    $product->update(['status' => 'Available']);
                }
            }

            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->back()->with('success', 'Cart cleared!');
    }

    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'qty'));
    }
}
