<?php

return [


    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

        'judge0' => [
        'url' => env('JUDGE0_URL'),
        'language_id' => env('JUDGE0_LANGUAGE_ID', 10),
        'token' => env('JUDGE0_TOKEN'),
        'auth_header' => env('JUDGE0_AUTH_HEADER', 'X-Auth-Token'),
        'timeout' => env('JUDGE0_TIMEOUT', 30),
        'cpu_time_limit' => env('JUDGE0_CPU_TIME_LIMIT', 2),
        'memory_limit' => env('JUDGE0_MEMORY_LIMIT', 128000),
        'max_processes_and_or_threads' => env('JUDGE0_MAX_PROCESSES_AND_OR_THREADS', 8),
        'per_process_time_limit' => env('JUDGE0_PER_PROCESS_TIME_LIMIT', true),
        'per_process_memory_limit' => env('JUDGE0_PER_PROCESS_MEMORY_LIMIT', true),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL').'/auth/google/callback'),
    ],

];
