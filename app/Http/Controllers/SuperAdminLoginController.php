<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserClient;

class SuperAdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('aaSuperAdmin/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = UserClient::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ]);
        }

        Auth::guard('userclient')->login($user);

        // 🔁 Redirect to omLandingPage after login
        return redirect()->route('client.landing');
    }

    public function logout(Request $request)
    {
        Auth::guard('userclient')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/signin');
    }

    public function profile()
    {
        $user = Auth::guard('userclient')->user();

        if (!$user) {
            return redirect('/signin');
        }

        return Inertia::render('Client/clientProfile', [
            'user' => $user,
        ]);
    }
}
