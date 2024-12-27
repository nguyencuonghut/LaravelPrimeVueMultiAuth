<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Auth/AdminLogin');
    }

    public function handleLogin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => 'Bạn phải nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Bạn phải nhập mật khẩu.'
        ];
        $credentials = $request->validate($rules, $messages);

        if (Auth::guard('admin')->attempt($credentials)) {
            //Check if status is Off
            if ('Off' == Auth::guard('admin')->user()->status) {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Tài khoản bị khóa!']);
            }

            $request->session()->regenerate();

            return redirect()->route('admin.home');
        }

        return back()->withErrors([
            'password' => 'Email hoặc mật khẩu không khớp!'
        ]);
    }

    public function handleLogout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}
