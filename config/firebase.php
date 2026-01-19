<?php

return [
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS', 'firebase_credentials.json'),
    ],
    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],
];
