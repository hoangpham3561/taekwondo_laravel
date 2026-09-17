<?php
// Build Vercel env payload from local .env (do not echo secrets)
$env = [];
foreach (file(dirname(__DIR__) . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
        continue;
    }
    [$k, $v] = explode('=', $line, 2);
    $k = trim($k);
    $v = trim($v);
    if (preg_match('/^"(.*)"$/', $v, $m) || preg_match("/^'(.*)'$/", $v, $m)) {
        $v = $m[1];
    }
    $env[$k] = $v;
}

$env['APP_ENV'] = 'production';
$env['APP_DEBUG'] = 'false';
$env['LOG_CHANNEL'] = 'stderr';
$env['LOG_LEVEL'] = 'error';
$env['QUEUE_CONNECTION'] = 'sync';

$required = [
    'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
    'LOG_CHANNEL', 'LOG_LEVEL',
    'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
    'SESSION_DRIVER', 'SESSION_LIFETIME', 'CACHE_STORE', 'QUEUE_CONNECTION',
    'FILESYSTEM_DISK', 'BROADCAST_CONNECTION',
    'MAIL_MAILER', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME', 'CLUB_CONTACT_EMAIL',
];

$sensitive = ['APP_KEY', 'DB_PASSWORD', 'DB_USERNAME', 'MAIL_PASSWORD', 'AWS_SECRET_ACCESS_KEY'];
$out = [];
$missing = [];
foreach ($required as $k) {
    if (!array_key_exists($k, $env) || $env[$k] === '') {
        $missing[] = $k;
        continue;
    }
    $type = in_array($k, $sensitive, true) ? 'sensitive' : 'encrypted';
    $out[] = [
        'key' => $k,
        'value' => $env[$k],
        'type' => $type,
        'target' => ['production', 'preview'],
    ];
}

file_put_contents('/tmp/vercel-env-payload.json', json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
$dbHost = $env['DB_HOST'] ?? '';
$isLocalDb = in_array($dbHost, ['127.0.0.1', 'localhost'], true);
echo 'keys=' . count($out) . PHP_EOL;
echo 'missing=' . implode(',', $missing) . PHP_EOL;
echo 'db_is_local=' . ($isLocalDb ? 'yes' : 'no') . PHP_EOL;
echo 'has_app_key=' . (!empty($env['APP_KEY']) ? 'yes' : 'no') . PHP_EOL;
