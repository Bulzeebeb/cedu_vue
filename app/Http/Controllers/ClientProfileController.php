<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\UserClient;
use App\Mail\OtpMail;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;


class ClientProfileController extends Controller
{
    public function __construct()
    {
        // Only protect routes that need authentication
       // $this->middleware('auth:userclient')->except(['profile', 'index']);
    }

    public function allClients()
    {
        $clients = UserClient::all()->map(function ($client) {
            $client->image_url = $client->image_path
                ? asset('storage/' . $client->image_path)
                : '/images/default-profile.png';
            return $client;
        });

        return response()->json($clients);
    }


    /**
     * Return all clients (no login required)
     */
    public function index()
    {
        $clients = UserClient::all()->map(function ($client) {
            $client->image_url = $client->image
                ? asset('storage/' . $client->image)
                : '/images/default-profile.png';
            return $client;
        });

        return response()->json($clients);
    }

    /**
     * Same as index() — returns all clients for backward compatibility
     */
    public function profile()
    {
        return $this->index();
    }

    /**
     * Update profile for logged-in client
     */
     public function update(Request $request)
    {
        /** @var UserClient $user */
        $user = Auth::guard('userclient')->user();

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'extension' => 'nullable|string|max:10',
            'contactNum' => 'required|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'age' => 'nullable|integer|min:0|max:120',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ Handle new image upload - FIXED: using 'image' instead of 'image_path'
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $path = $request->file('image')->store('profile_images', 'public');
            $user->image = $path; // FIXED: using existing 'image' column
        }

        // ✅ Update profile data
        $user->firstName = $request->firstName;
        $user->middleName = $request->middleName;
        $user->lastName = $request->lastName;
        $user->extension = $request->extension;
        $user->contactNum = $request->contactNum;
        $user->gender = $request->gender;
        $user->age = $request->age;
        $user->address = $request->address;

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function showChangePasswordForm()
    {
        return inertia('Client/changePassword');
    }

    public function sendPasswordOtp(Request $request)
    {
        $user = Auth::guard('userclient')->user();

        if (!$user) {
            return redirect('/signin')->withErrors(['auth' => 'Session expired. Please log in again.']);
        }

        if (!$user->email) {
            return response()->json(['error' => 'No email address is associated with this account.'], 400);
        }

        $otp = rand(100000, 999999);

        session([
            'password_otp' => $otp,
            'password_otp_email' => $user->email,
            'password_otp_time' => now()
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json(['status' => 'OTP sent']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'otp' => 'required',
        ]);

        /** @var UserClient|null $user */
        $user = Auth::guard('userclient')->user();

        if (!$user instanceof UserClient) {
            return redirect('/signin')->withErrors(['auth' => 'You must be logged in to change your password.']);
        }

        $otp = session('password_otp');
        $otpEmail = session('password_otp_email');
        $otpTime = session('password_otp_time');

        if (!$otp || !$otpEmail || !$otpTime) {
            return back()->withErrors(['otp' => 'OTP session expired. Please request a new OTP.']);
        }

        if (now()->diffInMinutes($otpTime) > 5) {
            session()->forget(['password_otp', 'password_otp_email', 'password_otp_time']);
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new OTP.']);
        }

        if ($otp !== $request->otp || $otpEmail !== $user->email) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget(['password_otp', 'password_otp_email', 'password_otp_time']);

        return back()->with('success', 'Password updated successfully.');
    }
}
