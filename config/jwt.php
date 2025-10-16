<?php

return [
    'secret' => env('JWT_SECRET'),
    'ttl' => 60,
    'refresh_ttl' => 20160,
    'algo' => 'HS256',
    'user' => 'App\Models\User',
    'identifier' => 'id',
    'required_claims' => ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti'],
    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),
    'providers' => [
        'jwt' => 'Tymon\JWTAuth\Providers\JWT\Lcobucci',
        'auth' => 'Tymon\JWTAuth\Providers\Auth\Illuminate',
        'storage' => 'Tymon\JWTAuth\Providers\Storage\Illuminate',
    ],
];