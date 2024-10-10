<?php

namespace App\GraphQL\Resolvers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AdminResolver
{
    public function all_user($_, array $args)
    {
        $users = User::all();
        $nonAdminUsers = [];
        foreach ($users as $user) {
            if (!$user->hasRole('Admin')) {
                $nonAdminUsers[] = $user;
            }
        }
        return $nonAdminUsers;
    }
}
