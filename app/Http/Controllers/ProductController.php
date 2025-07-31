<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'name' => 'required|string|max:255',
            'branch' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string'
        ]);

        // Store image
        $imagePath = $request->file('image')->store('public/products');
        $imageUrl = Storage::url($imagePath); // returns /storage/products/...

        // Create product
        $product = Product::create([
            'name' => $validated['name'],
            'branch' => $validated['branch'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'unit' => $validated['unit'],
            'status' => $validated['stock'] > 0 ? 'Available' : 'Out of Stock',
            'image' => $imageUrl
        ]);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ], 201);
    }

    public function index()
    {
        return response()->json(Product::all());
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

            $imagePath = $request->file('image')->store('public/products');
            $validated['image'] = Storage::url($imagePath);
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            $path = str_replace('/storage/', 'public/', $product->image);
            Storage::delete($path);
        }

        $product->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            if ($product->image) {
                $path = str_replace('/storage/', 'public/', $product->image);
                Storage::delete($path);
            }
            $product->delete();
        }

        return response()->json(['message' => 'Bulk delete successful']);
    }



}