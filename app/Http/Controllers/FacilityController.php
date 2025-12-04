<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities (API endpoint)
     */
    public function index(Request $request)
    {
        $query = Facility::with('category');

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by active status if provided
        if ($request->has('is_active') && $request->is_active != '') {
            $query->where('is_active', $request->is_active);
        }

        $facilities = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $facilities
        ]);
    }

    /**
     * Display facilities for hostel page
     */
    public function showHostel()
    {
        return $this->showFacilitiesByCategory('Hostel', 'Hostel');
    }

    /**
     * Display facilities for commercial page
     */
    public function showCommercial()
    {
        return $this->showFacilitiesByCategory('Commercial', 'Commercial');
    }

    /**
     * Display facilities for rental page
     */
    public function showRental()
    {
        return $this->showFacilitiesByCategory('Rental', 'Rental');
    }

    /**
     * Helper method to display facilities by category name
     */
    private function showFacilitiesByCategory($categoryName, $viewName)
    {
        // Get the category by name
        $category = Category::where('name', $categoryName)
            ->where('is_active', true)
            ->first();

        if (!$category) {
            // If no category exists, return empty array
            $facilities = [];
        } else {
            // Get all active facilities in this category
            $facilities = Facility::where('category_id', $category->id)
                ->where('is_active', true)
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($facility) {
                    // Add full image URLs
                    $facility->image_urls = collect($facility->images)->map(function ($image) {
                        return Storage::url($image);
                    });
                    return $facility;
                });
        }

        // Render with the correct path (UseFaci/Hostel, UseFaci/Rental, etc.)
        return Inertia::render('UseFaci/' . $viewName, [
            'auth' => [
                'user' => Auth::user(),
            ],
            'category' => $category,
            'facilities' => $facilities
        ]);
    }

    /**
     * Store a newly created facility
     */
    public function store(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Facility Store Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'details' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());

            // Get the first error message
            $firstError = $validator->errors()->first();

            return response()->json([
                'success' => false,
                'message' => $firstError,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if category is active
        $category = Category::find($request->category_id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        if (!$category->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add facility to disabled category'
            ], 400);
        }

        // Parse details JSON
        try {
            $details = json_decode($request->details, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid details format'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error parsing details: ' . $e->getMessage()
            ], 400);
        }

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            try {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('facilities', 'public');
                    $imagePaths[] = $path;
                }
            } catch (\Exception $e) {
                Log::error('Image upload failed:', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Image upload failed: ' . $e->getMessage()
                ], 500);
            }
        }

        try {
            $facility = Facility::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'details' => $details,
                'images' => $imagePaths,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Facility added successfully',
                'data' => $facility->load('category')
            ], 201);
        } catch (\Exception $e) {
            Log::error('Facility creation failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create facility: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified facility
     */
    public function show($id)
    {
        $facility = Facility::with('category')->find($id);

        if (!$facility) {
            return response()->json([
                'success' => false,
                'message' => 'Facility not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $facility
        ]);
    }

    /**
     * Update the specified facility
     */
    public function update(Request $request, $id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json([
                'success' => false,
                'message' => 'Facility not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'details' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if category is active
        $category = Category::find($request->category_id);
        if (!$category->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update facility with disabled category'
            ], 400);
        }

        // Parse details JSON
        try {
            $details = json_decode($request->details, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid details format'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error parsing details: ' . $e->getMessage()
            ], 400);
        }

        // Handle image uploads
        $imagePaths = $facility->images ?? [];
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($imagePaths as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('facilities', 'public');
                $imagePaths[] = $path;
            }
        }

        $facility->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'details' => $details,
            'images' => $imagePaths
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Facility updated successfully',
            'data' => $facility->load('category')
        ]);
    }

    /**
     * Toggle facility status
     */
    public function toggleStatus($id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json([
                'success' => false,
                'message' => 'Facility not found'
            ], 404);
        }

        $facility->update([
            'is_active' => !$facility->is_active
        ]);

        $status = $facility->is_active ? 'enabled' : 'disabled';

        return response()->json([
            'success' => true,
            'message' => "Facility {$status} successfully",
            'data' => $facility->load('category')
        ]);
    }

    /**
     * Remove the specified facility
     */
    public function destroy($id)
    {
        $facility = Facility::find($id);

        if (!$facility) {
            return response()->json([
                'success' => false,
                'message' => 'Facility not found'
            ], 404);
        }

        // Delete associated images
        if ($facility->images) {
            foreach ($facility->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $facility->delete();

        return response()->json([
            'success' => true,
            'message' => 'Facility deleted successfully'
        ]);
    }
}
