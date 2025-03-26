<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company\CompanyInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CompanyInvitationController extends Controller
{
    public function showJoinPage($token)
    {
        $invitation = CompanyInvitation::where('token', $token)
                                       ->where('expire_at', '>', now())
                                       ->firstOrFail();

        $roles = [
            ['id' => 'owner', 'label' => __('Owner / Founder')],
            ['id' => 'manager', 'label' => __('Hotel Manager')],
            ['id' => 'front-desk', 'label' => __('Front Desk / Receptionist')],
            ['id' => 'maintenance-staff', 'label' => __('Maintenance Staff')],
            ['id' => 'accountant', 'label' => __('Accountant')],
        ];
        $roles = Role::where('company_id', $invitation->company_id)->get();

        return view('auth.join', compact('invitation', 'roles'));
    }

    public function acceptInvitation(Request $request, $token)
    {
        $invitation = CompanyInvitation::where('token', $token)
                                       ->where('expire_at', '>', now())
                                       ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $invitation->email . ',email',
            'phone' => 'required|string|min:9|unique:users,phone',
            'password' => 'required|string|min:8',
        ]);

        // Role
        $role = Role::findByName($invitation->role);

        // Create the user if they don't exist
        $user = User::firstOrCreate(
            [
                'email' => $invitation->email,
                'current_company_id' => $invitation->company->id,
                'current_property_id' => $invitation->property->id,

            ],
            [
                'company_id' => $invitation->company->id,
                'current_company_id' => $invitation->company->id,
                'current_property_id' => $invitation->property->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]
        );

        // Assign the user to the company and role
        $user->assignRole($role->name);

        // Log the user in
        Auth::login($user);

        // Delete the invitation
        $invitation->delete();

        // Redirect to the dashboard or another appropriate page
        return redirect()->route('dashboard')->with('success', 'You have successfully joined the Company.');
    }
}
