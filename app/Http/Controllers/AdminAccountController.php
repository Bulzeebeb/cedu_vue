<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    /**
     * Display a listing of all admins.
     */
    public function index()
    {
        try {
            $admins = Admin::select([
                'id', 'first_name', 'middle_initial', 'last_name',
                'email', 'address', 'contact', 'gender', 'age', 'role',
                'created_at', 'updated_at'
            ])->orderBy('created_at', 'desc')->get();

            return response()->json($admins);
        } catch (\Exception $e) {
            Log::error('Error fetching admins: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch admins.'], 500);
        }
    }

    /**
     * Store a newly created admin.
     */
    public function store(Request $request)
    {
        Log::info('Incoming admin creation request:', $request->all());

        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_initial' => 'nullable|string|max:1',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:admins,email',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female',
            'age' => 'nullable|integer|min:18|max:99',
            'role' => 'required|in:Admin,Staff',
        ]);

        // Log password too (just for dev)
        $firstInitial = strtolower(substr($validated['first_name'], 0, 1) ?? 'x');
        $lastInitial = strtolower($validated['last_name'] ?? 'x');
        $passwordRaw = "{$firstInitial}{$lastInitial}123";

        Log::info('Password generated:', ['raw' => $passwordRaw]);

        $admin = Admin::create([
            ...$validated,
            'password' => Hash::make($passwordRaw),
        ]);

        return response()->json([
            'message' => "Admin created successfully. Password: {$passwordRaw}",
            'admin' => $admin,
            'password' => $passwordRaw
        ], 201);
    }

    /**
     * Display the specified admin.
     */
    public function show(Admin $admin)
    {
        return response()->json($admin);
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, Admin $admin)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:50',
                'middle_initial' => 'nullable|string|max:1',
                'last_name' => 'required|string|max:50',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('admins', 'email')->ignore($admin->id)
                ],
                'address' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:20',
                'gender' => 'nullable|in:Male,Female',
                'age' => 'nullable|integer|min:18|max:99',
                'role' => 'required|in:Admin,Staff',
            ]);

            $admin->update($validated);

            return response()->json([
                'message' => 'Admin updated successfully.',
                'admin' => $admin
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating admin: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update admin.'], 500);
        }
    }

    /**
     * Remove the specified admin.
     */
    public function destroy(Admin $admin)
    {
        try {
            // Optional: Prevent deleting own account
            if (auth('admin')->check() && auth('admin')->id() === $admin->id) {
                return response()->json(['message' => 'You cannot delete your own account.'], 403);
            }

            $admin->delete();

            return response()->json(['message' => 'Admin deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Error deleting admin: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete admin.'], 500);
        }
    }

    /**
     * Reset password for an admin.
     */
    public function resetPassword(Admin $admin)
    {
        try {
            $firstInitial = strtolower(substr($admin->first_name, 0, 1));
            $lastName = strtolower($admin->last_name);
            $newPassword = "{$firstInitial}{$lastName}123";

            $admin->update([
                'password' => Hash::make($newPassword),
            ]);

            return response()->json([
                'message' => "Password reset successfully. New password: {$newPassword}",
                'new_password' => $newPassword
            ]);
        } catch (\Exception $e) {
            Log::error('Error resetting admin password: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to reset password.'], 500);
        }
    }
}