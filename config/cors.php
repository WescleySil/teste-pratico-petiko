<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['GET','POST','PUT','DELETE','OPTIONS'],
    'allowed_origins' => ['teste-pratico-petiko-front.onrender.com'],
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
    'max_age' => 86400,
];

