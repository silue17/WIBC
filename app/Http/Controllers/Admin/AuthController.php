<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Identifiants par défaut (utilisés si rien en base)
    private const DEFAULT_EMAIL    = 'admin@wibc.ci';
    private const DEFAULT_PASSWORD = 'Wibc@2025';

    private function getCredentials(): array
    {
        $creds = SiteSetting::get('admin_credentials', null);
        if ($creds && isset($creds['email'], $creds['password'])) {
            return $creds;
        }
        return [
            'email'    => self::DEFAULT_EMAIL,
            'password' => self::DEFAULT_PASSWORD,
        ];
    }

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

        $creds = $this->getCredentials();

        if (
            $request->email    === $creds['email'] &&
            $request->password === $creds['password']
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

    public function credentialsShow()
    {
        $creds = $this->getCredentials();
        return response()->json(['email' => $creds['email']]);
    }

    public function credentialsUpdate(Request $request)
    {
        $request->validate([
            'email'  => 'required|email',
            'new_pw' => 'nullable|string|min:6',
        ]);

        $creds = $this->getCredentials();

        SiteSetting::set('admin_credentials', [
            'email'    => $request->email,
            'password' => $request->new_pw ?? $creds['password'],
        ]);

        return response()->json(['success' => true]);
    }
}
