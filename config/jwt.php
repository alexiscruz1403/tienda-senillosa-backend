<?php

return [
    'secret' => env('JWT_SECRET'),
    'algorithm' => 'HS256',
    'ttl' => 3600, // 1 hora
];
