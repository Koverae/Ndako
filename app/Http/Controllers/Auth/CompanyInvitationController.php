<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company\CompanyInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyInvitationController extends Controller
{
    public function showJoinPage($token)
    {
        $invitation = CompanyInvitation::where('token', $token)
                                       ->where('expire_at', '>', now())
                                       ->firstOrFail();

        return view('auth.join', compact('invitation'));
    }

    public function acceptInvitation(Request $request, $token)
    {
        $invitation = CompanyInvitation::where('token', $token)
                                       ->where('expire_at', '>', now())
                                       ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $invitation->email . ',email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user if they don't exist
        $user = User::firstOrCreate(
            ['email' => $invitation->email],
            [
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]
        );

        // Assign the user to the company and role
        $user->companies()->attach($invitation->company_id, ['role' => $invitation->role]);

        // Log the user in
        Auth::login($user);

        // Delete the invitation
        $invitation->delete();

        // Redirect to the dashboard or another appropriate page
        return redirect()->route('dashboard', ['subdomain' => current_company()->domain_name])->with('success', 'You have successfully joined the company.');
    }
}
