<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (auth()->guard('admins')->check()) {
            return redirect('admin');
        }
        if ($request->method() == 'GET') {
            return view('admin/login');
        }
        if ($request->isMethod('POST')) {
            $data = $request->only('user_name', 'password');
            if (auth()->guard('admins')->attempt($data)) {
                return response()->json(['message' => 'Giriş Başarılı'], 200);
            } else {
                return response()->json(['message' => 'Kullanıcı adı veya şifre yanlış'], 401);
            }
        }
    }
    public function logout() {
        auth()->guard('admins')->logout();
        return redirect('admin/giris');
    }
}
