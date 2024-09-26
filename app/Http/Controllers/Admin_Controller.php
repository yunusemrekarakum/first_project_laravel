<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;

class Admin_Controller extends Controller
{
//    public function admin_edit(Request $request) {
//        $data = $request->all();
//        $data = array_filter($data);
//        $admin = auth()->guard('admins')->user();
//        if (!empty($data)) {
//            if (!empty($data['old_password']) and !empty($data['password'])) {
//                if ($data['old_password'] === $data['password']) {
//                    return response()->json(['message' => 'Yeni şifre eski şifre ile aynı olamaz.'], 400);
//                }
//                if ($admin && Hash::check($data['old_password'], $admin->password)) {
//                    $admin->password = bcrypt($data['password']);
//                } else {
//                    return response()->json(['message' => 'Eski şifre hatalı.']);
//                }
//            }
//            if (!empty($data['name_surname'])) {
//                $admin->name_surname = $data['name_surname'];
//            }
//            if (!empty($data['user_name'])) {
//                $admin->user_name = $data['user_name'];
//            }
//            if (!empty($data['profile_image'])) {
//                $admin->profile_image = $data['profile_image'];
//            }
//            $admin->save();
//            return response()->json(['message' => 'Verileriniz Güncellendi.'], 200);
//        }
//    }
}
