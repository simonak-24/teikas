<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display login view.
     */
    public function login()
    {
        return view('user.login');
    }

    /**
     * Authenticates user.
     */
    public function authenticate(Request $request) : RedirectResponse
    {
        $credentials = $request->validate([
            'name' => 'max:32|required',
            'password' => 'max:32|required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        } else {
            return back()->withErrors([
                'password' => __('validation.credentials'),
            ]);
        }

        return back();
    }

    /**
     * Logs out user.
     */
    public function logout(Request $request) : RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
