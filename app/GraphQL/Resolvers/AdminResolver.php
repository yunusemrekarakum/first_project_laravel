<?php

namespace App\GraphQL\Resolvers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;
use Spatie\Permission\Models\Permission;

class AdminResolver
{
    public function all_user($_, array $args)
    {
        $users = User::with('permissions')->get();
        return $users;
    }
    public function permissions_get($_, array $args)
    {
        return Permission::all();
    }
}
