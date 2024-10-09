<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Hash;
use GraphQL\Type\Definition\ResolveInfo;

class AdminLogin
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $admin = User::where('user_name', $args['user_name'])->first();

        if (!$admin || !Hash::check($args['password'], $admin->password)) {
            throw new \GraphQL\Error\Error('Geçersiz Kimlik Bilgileri');
        }

        $token = $admin->createToken(
            'Admin Token', ['*'], now()->addMinutes(120)
        )->plainTextToken;

        return [
            'token' => $token,
            'admin' => $admin,
        ];
    }
}
