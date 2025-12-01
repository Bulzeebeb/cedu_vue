<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::withCount('facilities')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get only active categories
     */
    public function getActive()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Disable/Enable the specified category
     */
    public function toggleStatus($id)
    {
        $category = Category::withCount('facilities')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        // If disabling, also disable all facilities
        if ($category->is_active) {
            $category->facilities()->update(['is_active' => false]);
        }

        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'enabled' : 'disabled';

        return response()->json([
            'success' => true,
            'message' => "Category {$status} successfully",
            'data' => $category
        ]);
    }

    /**
     * Get a single category
     */
    public function show($id)
    {
        $category = Category::withCount('facilities')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}
