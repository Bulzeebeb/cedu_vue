<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use Inertia\Inertia;

class OnlineMarketInventoryController extends Controller
{
    public function adminIndex()
    {
        $products = Product::all();

        return Inertia::render('OnlineMarket_ADMIN/adminInventory', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'name' => 'required|string|max:255',
            'branch' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string'
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'_'.$request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('products', $imageName, 'public');
            $validated['image'] = Storage::url($imagePath);
        }

        Product::create([
            'name' => $validated['name'],
            'branch' => $validated['branch'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'unit' => $validated['unit'],
            'status' => $validated['stock'] > 0 ? 'Available' : 'Out of Stock',
            'image' => $validated['image']
        ]);

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'status' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::exists(str_replace('/storage', 'public', $product->image))) {
                Storage::delete(str_replace('/storage', 'public', $product->image));
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = Storage::url($imagePath);
        }

        $product->update($validated);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            $path = str_replace('/storage/', 'public/', $product->image);
            Storage::delete($path);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'No products selected.');
        }

        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            if ($product->image) {
                $path = str_replace('/storage/', 'public/', $product->image);
                Storage::delete($path);
            }
            $product->delete();
        }

        return redirect()->back()->with('success', 'Selected products deleted successfully!');
    }

    public function showFruits()
    {
        // Option 2: Hide out of stock products (your preference)
        $products = Product::where('category', 'Fruits')
                         ->where('stock', '>', 0)
                         ->get();

        $cartCount = $this->getCartCount();

        return Inertia::render('OnlineMarket/fruitPage', [
            'products' => $products,
            'cartCount' => $cartCount
        ]);
    }

    public function showPoultry()
    {
        // Option 2: Hide out of stock products (your preference)
        $products = Product::where('category', 'Poultry')
                         ->where('stock', '>', 0)
                         ->get();

        $cartCount = $this->getCartCount();

        return Inertia::render('OnlineMarket/poultryPage', [
            'products' => $products,
            'cartCount' => $cartCount
        ]);
    }

    public function showVegetables()
    {
        // Option 2: Hide out of stock products (your preference)
        $products = Product::where('category', 'Vegetables')
                         ->where('stock', '>', 0)
                         ->get();

        $cartCount = $this->getCartCount();

        return Inertia::render('OnlineMarket/vegetablePage', [
            'products' => $products,
            'cartCount' => $cartCount
        ]);
    }

    private function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'qty'));
    }
}
