<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Request;

class AuthController extends Controller
{
    public function getUserRole(Request $request)
    {
        $user = $request->user();
        return response()->json(['role' => $user->role]); // Kullanıcının rolü burada dönülüyor
    }
}
