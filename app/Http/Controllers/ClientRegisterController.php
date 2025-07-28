<?php

namespace App\Http\Controllers;

use App\Models\UserClient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpVerificationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ClientRegisterController extends Controller
{
    // Step 1: Show personal info form
    public function step1()
    {
        return Inertia::render('Client/Signup1');
    }

    // Step 2: Show account info form, pass data from step 1
    public function step2(Request $request)
    {
        return Inertia::render('Client/Signup2', [
            'formData' => $request->all()
        ]);
    }

    // Step 3: Store data temporarily in session + send OTP
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName'     => 'required|string|max:100',
            'lastName'      => 'required|string|max:100',
            'middleName'    => 'nullable|string|max:100',
            'extension'     => 'nullable|string|max:10',
            'address'       => 'required|string|max:255',
            'contactNum'    => 'required|numeric',
            'gender'        => 'required|in:Male,Female,Other',
            'age'           => 'required|integer|min:1|max:150',
            'email'         => 'required|email|unique:user_client,email',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        $otp = strval(rand(100000, 999999)); // Store OTP as string for accurate comparison

        // Store in session
        session([
            'otp_code'       => $otp,
            'otp_created_at' => now(),
            'user_data'      => array_merge($validated, [
                'password' => Hash::make($validated['password']),
            ])
        ]);

        // Send OTP
        Mail::to($validated['email'])->send(new OtpVerificationMail($otp));

        return Inertia::location('/signup/verify-otp');
    }

    // Show OTP verification view
    public function showVerifyOtp()
    {
        return Inertia::render('Client/VerifyOtp');
    }

    // Final step: Verify OTP and create account
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $otp        = session('otp_code');
        $created_at = session('otp_created_at');
        $data       = session('user_data');

        if (!$otp || !$data || !$created_at) {
            return back()->withErrors(['otp' => 'Session expired. Please try registering again.']);
        }

        if ($request->otp !== $otp) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        if (now()->gt(Carbon::parse($created_at)->addMinutes(5))) {
            return back()->withErrors(['otp' => 'OTP has expired. Please register again.']);
        }

        // ✅ Create the user
        UserClient::create([
            'email'      => $data['email'],
            'password'   => $data['password'],
            'firstName'  => $data['firstName'],
            'lastName'   => $data['lastName'],
            'middleName' => $data['middleName'] ?? null,
            'extension'  => $data['extension'] ?? null,
            'address'    => $data['address'],
            'contactNum' => $data['contactNum'],
            'gender'     => $data['gender'],
            'age'        => $data['age'],
        ]);

        // Clear session
        session()->forget(['otp_code', 'otp_created_at', 'user_data']);

        return redirect('/signin')->with('success', 'Your account has been verified and created.');
    }

    // Resend OTP (with session check)
    public function resendOtp()
    {
        $data = session('user_data');

        if (!$data || !isset($data['email'])) {
            return back()->withErrors(['otp' => 'Session expired. Please restart registration.']);
        }

        $otp = strval(rand(100000, 999999));

        session([
            'otp_code'       => $otp,
            'otp_created_at' => now(),
        ]);

        Mail::to($data['email'])->send(new OtpVerificationMail($otp));

        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}
