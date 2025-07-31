<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\UserClient;

class AuthController extends Controller
{
    /**
     * Show the universal login form
     */
    public function showLoginForm()
    {
        return Inertia::render('LandingPage/signmain');
    }

    /**
     * Handle universal login: SuperAdmin, Admin, or Client
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $credentials['email'];
        $password = $credentials['password'];

        // 🔐 Hardcoded SuperAdmin
        if ($email === 'superadmin@cedu.com' && $password === 'superadmin123') {
            $superAdmin = new \stdClass();
            $superAdmin->id = 0;
            $superAdmin->first_name = 'Super';
            $superAdmin->last_name = 'Admin';
            $superAdmin->email = $email;
            $superAdmin->role = 'SuperAdmin';

            session(['admin' => $superAdmin]);

            Log::info('✅ SuperAdmin login successful');
            return redirect('/superadmindashboard');
        }

        // 🔑 Try admin login
        $admin = Admin::where('email', $email)->first();

        if ($admin && Hash::check($password, $admin->password)) {
            Auth::guard('admin')->login($admin);

            Log::info('✅ Admin login successful', ['role' => $admin->role]);

            return match ($admin->role) {
                'SuperAdmin' => redirect('/superadmindashboard'),
                'Admin' => redirect('/adminchoice'),
                'Staff' => redirect('/staffdashboardPayToPark'),
                default => back()->withErrors(['message' => 'Unknown admin role.']),
            };
        }

        // 👤 Try client login
        $client = UserClient::where('email', $email)->first();

        if ($client && Hash::check($password, $client->password)) {
    Auth::guard('userclient')->login($client);

    Log::info('✅ Client login successful');
    return redirect()->route('client.landing'); // ✅ fixed here
}


        // ❌ Invalid login
        Log::warning('❌ Login failed for email: ' . $email);
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    /**
     * Admin/Client Logout (auto-detect guard)
     */
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            $id = Auth::guard('admin')->id();
            Auth::guard('admin')->logout();
            Log::info('🔓 Admin logged out', ['id' => $id]);
        } elseif (Auth::guard('userclient')->check()) {
            $id = Auth::guard('userclient')->id();
            Auth::guard('userclient')->logout();
            Log::info('🔓 Client logged out', ['id' => $id]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/signmain');
    }

    /**
     * Client profile
     */
    public function clientProfile()
    {
        $user = Auth::guard('userclient')->user();

        return $user
            ? Inertia::render('Client/clientProfile', ['user' => $user])
            : redirect('/signmain');
    }

    /**
     * Client setting
     */
    public function clientSetting()
    {
        $user = Auth::guard('userclient')->user();

        return $user
            ? Inertia::render('Client/clientSetting', ['client' => $user])
            : redirect('/signmain');
    }
}
