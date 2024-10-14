<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User as user_model;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class User
{
    public function user_create($_, array $args)
    {
        $existingEmail = user_model::where('email', $args['email'])->first();
        if ($existingEmail) {
            throw new Exception('Bu email ile daha önceden kayıt olundu');
        }
        $user = user_model::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => bcrypt($args['password']),
        ]);
        $user->assignRole('User');

        $token = $user->createToken(
            'user_token',
            ['*'],
            now()->addMinutes(120)
        )->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function get_user($_, array $args)
    {
        $user_info = Auth::guard('user')->user();
        if (! $user_info) {
            throw new Exception('Hesap bulunamadı');
        }

        return $user_info;
    }

    public function login($_, array $args)
    {
        $user = user_model::where('email', $args['email'])->first();

        if (! $user || ! Hash::check($args['password'], $user->password)) {
            throw new \GraphQL\Error\Error('Geçersiz Kimlik Bilgileri');
        }

        $token = $user->createToken(
            'user_token',
            ['*'],
            now()->addMinutes(120)
        )->plainTextToken;

        return [
            'token' => $token,
        ];
    }

    public function user_edit($_, array $args)
    {
        // user'i bul
        $user = Auth::guard('user')->user();

        if (! $user) {
            throw new \Exception('Hesap Bulunamadı');
        }
        if (isset($args['old_password']) or isset($args['password'])) {
            if (! Hash::check($args['old_password'], $user->password)) {
                throw new \Exception('Eski Şifre Hatalı');
            }
            if (isset($args['password'])) {
                $user->password = bcrypt($args['password']);
            } else {
                throw new Exception("Yeni Şifre Alanı Doldurulmalı");
            }
        }
        // Verileri güncelle
        if (isset($args['name'])) {
            $user->name = $args['name'];
        }
        if (isset($args['email'])) {
            $user->email = $args['email'];
        }
        if (isset($args['profile_image'])) {
            $newFileName = 'profile_' . time() . '_' . $user->id . '.' . $args['profile_image']->getClientOriginalExtension();
            $last_media = $user->addMedia($args['profile_image'])->setFileName($newFileName)->toMediaCollection('profile', 'media');
            $user->profile_image = $last_media->getUrl();
        }

        $user->save();

        return $user;
    }
    public function get_user_role($_, array $args)
    {
        $user = Auth::guard('user')->user();
        if (!$user) {
            throw new Exception("Kullanıcı oturumu açık değil.", 401); // Hata kodunu 401 yaparak daha anlamlı hale getirebilirsiniz
        }

        $role = $user->getRoleNames()->first();
        if (!$role) {
            throw new Exception("Kullanıcının rolü bulunamadı.");
        }
        return [
            'role' => $role
        ];
    }
    public function Admin_authority($_, array $args)
    {
        $userıd = $args['id'];
        $user = user_model::find($userıd);
        if (!$user->hasRole('Admin')) {
            $user->removeRole('User');
            $user->assignRole('Admin');
            return [
                'success' => true,
                'message' => 'Admin rolü kaldırıldı.',
            ];
        } else {
            $user->removeRole('Admin');
            $user->assignRole('User');
            return [
                'success' => true,
                'message' => 'Admin rolü başarıyla verildi.',
            ];
        }
    }
    public function delete_user($_, array $args)
    {
        $id = $args['id'];
        $user = user_model::findOrFail($id);
        $user->delete();
        return true;
    }
}
