<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->only('user_name', 'password');
        return response()->json([auth()->guard('admins')]);
        //if (auth()->guard('admins')->attempt($data)) {
          //  return response()->json(['message' => 'Giriş Başarılı Yönlendiriliyorsunuz...'], 200);
        //} else {
        //    return response()->json(['message' => 'Kulanıcı Adı veya Şifre Yanlış'], 401);
        //}
    }
}
