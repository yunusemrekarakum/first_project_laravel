<?php

namespace App\GraphQL\Resolvers;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AdminResolver
{
    public function __invoke($rootValue, array $args, $context, ResolveInfo $info)
    {
        $admin = Admin::where('user_name', $args['user_name'])->first();

        if (!$admin || !Hash::check($args['password'], $admin->password)) {
            throw new \Exception('Geçersiz kullanıcı adı veya şifre.');
        }

        $token = $admin->createToken($args['Admin Token'])->plainTextToken;

        $tokenResult = $admin->createToken($args['Admin Token'], ['*']);
        $token = $tokenResult->token;
        $token->expires_at = now()->addMinutes(120);
        $token->save();
        return [
            'token' => $token,
            'admin' => $admin,
        ];
    }

    public function getAdmin($root, array $args)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            throw new \Exception('Unauthorized');
        }
        return $admin;
    }

    public function updateAdmin($root, array $args)
    {
        // Admin'i bul
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            throw new \Exception('Hesap Bulunamadı');
        }

        if (isset($args['old_password'])) {
            if (!Hash::check($args['old_password'], $admin->password)) {
                throw new \Exception('Eski Şifre Hatalı');
            }
            if (isset($args['password'])) {
                $admin->password = bcrypt($args['password']); // Şifreyi hash'le
            }
        }
        // Verileri güncelle
        if (isset($args['name_surname'])) {
            $admin->name_surname = $args['name_surname'];
        }
        if (isset($args['user_name'])) {
            $admin->user_name = $args['user_name'];
        }
        if (isset($args['profile_image'])) {
            $newFileName = 'profile_' . time() . '_' . $admin->id . '.' . $args['profile_image']->getClientOriginalExtension();
            $last_media = $admin->addMedia($args['profile_image'])->setFileName($newFileName)->toMediaCollection('profile', 'media');
            $admin->profile_image = $last_media->getUrl();
        }

        $admin->save();

        return $admin;
    }
}
