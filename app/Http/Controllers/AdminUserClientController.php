<?php

namespace App\Http\Controllers;

use App\Models\UserClient;
use Illuminate\Http\Request;

class AdminUserClientController extends Controller
{

    public function index()
    {
        // Fetch all user client accounts
        $clients = UserClient::all();
        return response()->json($clients);
    }

    public function destroy($id)
    {
        $user = UserClient::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
