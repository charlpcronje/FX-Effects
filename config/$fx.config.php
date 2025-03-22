<?php
return (object)[
    // APP CONFIGURATION
    'app' => [
            'path_fx' => __DIR__,
            'name'    => 'Your App Name',
            'version' => '1.0.0',
            'debug'   => true,                // Set to false in production
    ],

    // DATABASE CONFIGURATION
    'database' => [
        'driver'   => 'mysql',               // Change to 'pgsql', 'sqlite', etc. as needed
        'host'     => 'localhost',
        'port'     => 3306,
        'dbname'   => 'fx_database',
        'username' => 'fx_user',
        'password' => 'securepassword',
        'charset'  => 'utf8mb4',
    ],

    // DEBUGGING & LOGGING
    'debug' => [
        'enabled' => true,                      // Set to false in production
        'log_file' => __DIR__ . '/logs/fx.log', // Path to log file
        'display_errors' => true,               // Show errors on screen (false for production)
    ],

    // FX NODE CONFIGURATION
    'fx' => [
        'default_exec_timing' => 'set',        // Default event timing for effects
        'global_registry' => true,             // Enable tracking of all nodes globally
    ],

    // CACHING CONFIGURATION
    'cache' => [
        'enabled' => true,
        'driver'  => 'file',                  // Options: file, redis, memcached, apcu
        'path'    => __DIR__ . '/cache',      // Cache directory (for file cache)
        'ttl'     => 3600,                    // Default time-to-live for cached items (in seconds)
    ],

    // WEB SOCKET CONFIGURATION
    'websocket' => [
        'enabled' => true,
        'server'  => 'ws://localhost:8080',
        'auth_required' => false,             // Set to true if authentication is required
    ],

    // SECURITY & ACCESS CONTROL
    'security' => [
        'cors_allowed_origins' => ['*'], // Restrict to specific domains if needed
        'allowed_api_keys' => [
            'example-api-key-123',
            'another-api-key-456',
        ],
    ],

    // CUSTOM EFFECTS (Register Global Plugins)
    'effects' => [
        'default' => [
            'db' => \fx\DBEffect::class,
            'lazy_db' => \fx\LazyDBEffect::class,
            'verify' => \fx\VerifyEffect::class,
            'ws' => \fx\wsEffect::class,
        ]
    ]
];