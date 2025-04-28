<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($credentials['email'] === env('ADMIN_EMAIL') && $credentials['password'] === env('ADMIN_PASSWORD')) {
            $user = ['id' => 1, 'name' => 'Admin', 'email' => $credentials['email']];
            session(['admin_user' => $user]); // Simpan di session
    
            Auth::login(new GenericUser($user)); // Login manual
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Kredensial login salah.');
    }

    public function logout() {
        session()->forget('admin_user');
        return redirect()->route('login');
    }
}
