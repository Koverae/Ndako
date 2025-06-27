<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:15', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        
        event(new Registered($user));

        Auth::login($user);

        // If the user doesn't have a phone number, redirect them to complete profile
        if (!$user->phone) {
            return redirect(route('complete-phone'));
        }

        // return redirect(route('dashboard', absolute: false));
        return redirect(route('getting-started', absolute: false));
    }

    public function completePhoneForm()
    {
        return view('auth.complete-phone');
    }

    public function storePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits_between:10,15|unique:users,phone',
        ]);

        $user = User::find(Auth::user());
        $user->phone = $request->phone;
        $user->save();

        return redirect(route('getting-started', absolute: false));
    }

}
