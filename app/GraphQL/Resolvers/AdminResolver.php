<?php

namespace App\GraphQL\Resolvers;

use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;
use Spatie\Permission\Models\Permission;
use stdClass;

class AdminResolver
{
    public function all_user($_, array $args)
    {
        $users = User::with(['permissions', 'roles'])
            ->where('id', '!=', 1)
            ->get();
        return $users;
    }
    public function permissions_get($_, array $args)
    {
        return Permission::all();
    }
    public function permission_add($_, array $args)
    {
        $user = User::find($args['user_id']);
        $permission = Permission::find($args['permission_id']);

        if ($user->hasPermissionTo($permission->name)) {
            $user->revokePermissionTo($permission->name);
        } else {
            $user->givePermissionTo($permission->name);
        }
        return $user->load('permissions');
    }
    public function user_permissions()
    {
        $users = Auth::guard("user")->user();
        $permissions = $users->permissions()->pluck('name');
        return [
            'name' => $permissions
        ];
    }
    public function getAllUser($_, array $args)
    {
        $users = User::all();
        return $users;
    }
    public function notification_send($_, array $args)
    {
        $user = User::find($args['user_id']);
        $notification = new stdClass();
        $notification->amount = $args['notification'];

        if (!$user) {
            return false;
        }

        $user->notify(new InvoicePaid($notification));

        return true;
    }
    public function user_get_notification($_, array $args)
    {
        $user = Auth::guard("user")->user();
        $notifications = $user->notifications->map(function ($notification) {
            $notification->data = json_encode($notification->data); // JSON verisini string'e çevir
            return $notification;
        });
        return $notifications;
    }
}
