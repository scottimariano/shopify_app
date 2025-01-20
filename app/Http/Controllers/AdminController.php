<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Toggle the daily report status for a user.
     */
    public function toogleDailyReportSuscription(int $userId)
    {
        $user = User::findOrFail($userId);

        $user->daily_report = !$user->daily_report;
        $user->save();

        return response()->json([
            'message' => 'Daily report status updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Update the email for a user.
     */
    public function updateUserEmail(Request $request, int $userId)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,'
        ]);

        $user = User::findOrFail($userId);
        $user->email = $validatedData['email'];
        $user->save();

        return response()->json([
            'message' => 'Email updated successfully.',
            'user' => $user,
        ]);
    }
}
