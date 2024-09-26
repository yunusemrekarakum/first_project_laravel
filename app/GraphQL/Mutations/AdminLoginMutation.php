<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminLoginMutation
{
    public function __invoke($root, array $args, $context, ResolveInfo $info)
    {
        $admin = Admin::where('user_name', $args['user_name'])->first();

        if (!$admin || !password_verify($args['password'], $admin->password)) {
            throw new \Exception('Invalid credentials');
        }

        // Token oluşturma
        $token = $admin->createToken('Admin Token')->plainTextToken;

        return [
            'token' => $token,
            'admin' => $admin,
        ];
    }
}
