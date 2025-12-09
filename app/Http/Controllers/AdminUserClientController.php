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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user = UserClient::findOrFail($id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'User status updated successfully', 'status' => $user->status]);
    }

    public function destroy($id)
    {
        $user = UserClient::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
