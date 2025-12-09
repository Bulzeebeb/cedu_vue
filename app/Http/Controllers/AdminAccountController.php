<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class AdminAccountController extends Controller
{
    /**
     * Display a listing of all admins.
     */
    public function index()
    {
        $admins = Admin::select([
            'id',
            'first_name',
            'middle_initial',
            'last_name',
            'email',
            'address',
            'contact',
            'gender',
            'age',
            'role',
            'profile_picture',
            'status',
            'raw_password',
            'created_at'
        ])->orderBy('created_at', 'desc')->get();

        $admins->transform(function ($admin) {
            $admin->profile_picture = $admin->profile_picture
                ? asset('storage/' . $admin->profile_picture)
                : null;
            return $admin;
        });

        return response()->json($admins);
    }

    public function show($id)
    {
        $admin = Admin::select([
            'id',
            'first_name',
            'middle_initial',
            'last_name',
            'email',
            'address',
            'contact',
            'gender',
            'age',
            'role',
            'profile_picture',
            'status',
            'raw_password',
            'created_at'
        ])->findOrFail($id);

        $admin->profile_picture = $admin->profile_picture
            ? asset('storage/' . $admin->profile_picture)
            : null;

        return response()->json($admin);
    }

    public function store(Request $request)
    {
        // Log the incoming payload for debugging
        Log::info('Incoming admin creation request:', $request->all());

        try {
            // Validate the incoming request
            $validated = $request->validate([
                'first_name' => 'required|string|max:50',
                'middle_initial' => 'nullable|string|max:1',
                'last_name' => 'required|string|max:50',
                'email' => 'required|email|unique:admins,email',
                'address' => 'nullable|string|max:255',
                'contact' => 'nullable|string|min:5|max:20',
                'gender' => 'nullable|in:Male,Female,Other',
                'age' => 'nullable|integer|min:18|max:99',
                'role' => 'required|in:Admin,Staff',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'nullable|string|min:6',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        // If password is missing, auto-generate one
        if (empty($validated['password'])) {
            $firstInitial = strtolower(substr($validated['first_name'], 0, 1) ?? 'x');
            $lastName = strtolower($validated['last_name'] ?? 'x');
            $validated['password'] = "{$firstInitial}{$lastName}123";
        }
        $rawPassword = $validated['password'];

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            try {
                $validated['profile_picture'] = $request->file('profile_picture')
                    ->store('admin_profiles', 'public');
                Log::info('Profile picture uploaded:', ['path' => $validated['profile_picture']]);
            } catch (\Exception $e) {
                Log::error('Profile picture upload failed:', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload profile picture.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        try {
            // Create the new admin record
            $account = Admin::create([
                'first_name' => $validated['first_name'],
                'middle_initial' => $validated['middle_initial'] ?? null,
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'address' => $validated['address'] ?? null,
                'contact' => $validated['contact'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'age' => $validated['age'] ?? null,
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
                'raw_password' => $rawPassword,
                'status' => 'enabled',
                'profile_picture' => $validated['profile_picture'] ?? null,
            ]);

            Log::info('Admin account created successfully:', ['id' => $account->id, 'email' => $account->email]);

            // Log activity
            $this->logActivity('Super Admin', 'Admin', 'Created new account: ' . $account->email);

            // Transform profile picture URL for response
            $account->profile_picture = $account->profile_picture
                ? asset('storage/' . $account->profile_picture)
                : null;

            // Return success response with consistent structure
            return response()->json([
                'success' => true,
                'message' => 'Account created successfully',
                'account' => $account,
                'generated_password' => $rawPassword
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating admin account:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create account. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:50',
                'middle_initial' => 'nullable|string|max:1',
                'last_name' => 'required|string|max:50',
                'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($admin->id)],
                'address' => 'nullable|string|max:255',
                'contact' => 'nullable|string|min:5|max:20',
                'gender' => 'nullable|in:Male,Female,Other',
                'age' => 'nullable|integer|min:18|max:99',
                'role' => 'required|in:Admin,Staff',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'nullable|string|min:6',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($admin->profile_picture) {
                Storage::disk('public')->delete($admin->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')
                ->store('admin_profiles', 'public');
        }

        // Handle password update
        if (!empty($validated['password'])) {
            $validated['raw_password'] = $validated['password'];
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        try {
            $admin->update($validated);

            // Log activity
            $this->logActivity('Super Admin', 'Admin', 'Updated account: ' . $admin->email);

            return redirect()->back()->with('success', 'Admin profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating admin: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update admin profile. Please try again later.');
        }
    }

    /**
     * Remove the specified admin.
     */
    public function destroy($id)
    {
        try {
            $admin = Admin::findOrFail($id);

            // Optional: Prevent deleting own account
            if (auth('admin')->check() && auth('admin')->id() === $admin->id) {
                return response()->json(['message' => 'You cannot delete your own account.'], 403);
            }

            // Log activity before deletion
            $this->logActivity('Super Admin', 'Admin', 'Deleted account: ' . $admin->email);

            // Delete profile picture if exists
            if ($admin->profile_picture) {
                Storage::disk('public')->delete($admin->profile_picture);
            }

            $admin->delete();

            return response()->json(['message' => 'Admin deleted successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting admin: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete admin.'], 500);
        }
    }

    /**
     * Reset password for an admin.
     */
    public function resetPassword($id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $firstInitial = strtolower(substr($admin->first_name, 0, 1) ?? 'x');
            $lastName = strtolower($admin->last_name ?? 'x');
            $newPassword = "{$firstInitial}{$lastName}123";

            $admin->fill([
                'password' => Hash::make($newPassword),
                'raw_password' => $newPassword,
            ])->save();

            $this->logActivity('Super Admin', 'Admin', 'Reset password for account: ' . $admin->email);

            return response()->json([
                'message' => "Password reset successfully. New password: {$newPassword}",
                'new_password' => $newPassword
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error resetting admin password: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to reset password.'], 500);
        }
    }

    /**
     * Update status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            Log::info('Status update request received', [
                'id' => $id,
                'payload' => $request->all(),
                'raw' => $request->getContent(),
            ]);

            $validated = $request->validate([
                'status' => 'required|in:enabled,disabled',
            ]);

            $admin = Admin::findOrFail($id);
            $admin->status = $validated['status'];
            $admin->save();

            $this->logActivity('Super Admin', 'Admin', 'Changed status to ' . $validated['status'] . ' for account: ' . $admin->email);

            Log::info('Status updated successfully', [
                'id' => $id,
                'new_status' => $admin->status
            ]);

            return response()->json([
                'message' => 'Status updated successfully',
                'status' => $admin->status
            ], 200);
        } catch (ValidationException $ve) {
            Log::error('Validation error updating admin status', [
                'id' => $id,
                'errors' => $ve->errors(),
                'payload' => $request->all()
            ]);
            return response()->json([
                'message' => 'Validation error',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating admin status: ' . $e->getMessage(), [
                'id' => $id,
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Activity log
     */
    private function logActivity($name, $role, $action)
    {
        ActivityLog::create([
            'name' => $name,
            'role' => $role,
            'action' => $action,
        ]);
    }

    // Other existing methods remain the same...
    public function fetchProfileWithActions()
    {
        $admin = Admin::where('role', 'Super Admin')->first();
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        return response()->json(['profile' => $admin, 'actions' => []]);
    }

    public function showProfile()
    {
        $admin = Admin::where('role', 'Admin')->first();
        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }
        return response()->json($admin);
    }

    public function updateProfile(Request $request)
    {
        try {
            $admin = auth('admin')->user();

            if (!$admin) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Debug: Log the incoming request data
            Log::info('UpdateProfile Request Data:', $request->all());
            Log::info('UpdateProfile Form Data:', $request->input());

            $validated = $request->validate([
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
                'middle_initial' => 'nullable|string|max:1',
                'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($admin->id)],
                'address' => 'required|string|max:255',
                'contact' => 'required|string|min:5|max:20',
                'gender' => 'required|in:Male,Female,Other',
                'age' => 'required|integer|min:18|max:99',
                'password' => 'nullable|string|min:6',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            // Remove role from validated data since admins cannot change their own role
            unset($validated['role']);

            // Handle password update
            if (!empty($validated['password'])) {
                $validated['raw_password'] = $validated['password'];
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($admin->profile_picture) {
                    Storage::disk('public')->delete($admin->profile_picture);
                }
                $validated['profile_picture'] = $request->file('profile_picture')
                    ->store('admin_profiles', 'public');
            }

            $admin->fill($validated)->save();

            // Log activity
            $this->logActivity($admin->first_name . ' ' . $admin->last_name, $admin->role, 'Updated own profile');

            return redirect()->back()->with('success', 'Profile updated successfully');

        } catch (ValidationException $ve) {
            Log::error('Validation failed for updateProfile:', $ve->errors());
            return redirect()->back()->withErrors($ve->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating admin profile: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update profile. Please try again.']);
        }
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'id' => 'required|exists:admins,id',
        ]);

        $user = Admin::findOrFail($request->id);

        if ($request->hasFile('photo')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $photoPath = $request->file('photo')->store('admin_profiles', 'public');
            $user->profile_picture = $photoPath;
            $user->save();

            return response()->json(['photo_url' => asset('storage/' . $photoPath)]);
        }

        return response()->json(['error' => 'No photo uploaded.'], 422);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = Admin::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json(['message' => 'Login successful', 'user' => $user]);
    }
}