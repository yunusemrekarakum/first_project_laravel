<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Mutation',
            'fields' => [
                'adminLogin' => [
                    'type' => Type::nonNull(new AdminLoginResponseType()), // AdminLoginResponseType'ı tanımladığınızdan emin olun
                    'args' => [
                        'user_name' => Type::nonNull(Type::string()),
                        'password' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => new AdminLoginMutation(), // AdminLoginMutation sınıfını burada kullanıyoruz
                ],
            ],
        ];

        parent::__construct($config);
    }
}
