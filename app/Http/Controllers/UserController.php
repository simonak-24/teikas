<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Display a listing of all users.
     */
    public function index()
    {
        $users = User::all()->sortBy('name');
        return view('user.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate( [
            'name' => 'max:32|required|unique:users,name',
            'password' => 'max:32|confirmed|required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(string $id)
    {
        User::findOrfail($id)->delete();
        return redirect()->route('user.index');
    }
}
