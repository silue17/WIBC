<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Identifiants admin (à changer selon vos besoins)
    private const ADMIN_EMAIL    = 'admin@wibc.ci';
    private const ADMIN_PASSWORD = 'Wibc@2025';

    public function loginPage()
    {
        if (session('admin_logged_in')) {
            return redirect('/admin');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (
            $request->email    === self::ADMIN_EMAIL &&
            $request->password === self::ADMIN_PASSWORD
        ) {
            session(['admin_logged_in' => true, 'admin_email' => $request->email]);
            return redirect('/admin');
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->withInput();
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_email']);
        return redirect('/admin/login');
    }
}
