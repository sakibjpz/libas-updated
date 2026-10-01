<?php
/**
 * One-time deployment fixer — upload to public/, run once via browser, then DELETE.
 * URL: https://libasbd.com/fix-deploy.php?key=libas-fix-9281
 */

$SECRET = 'libas-fix-9281';

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    exit('Forbidden.');
}

header('Content-Type: text/plain; charset=utf-8');
set_time_limit(300);

$base = dirname(__DIR__);
echo "LibasBD deploy fix\n===================\n\n";

try {
    require $base . '/vendor/autoload.php';
    $app = require $base . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // 1) Clear caches first (so stale config/routes don't block boot)
    foreach (['config:clear', 'route:clear', 'view:clear', 'cache:clear'] as $cmd) {
        try {
            Artisan::call($cmd);
            echo "[OK] $cmd\n";
        } catch (\Throwable $e) {
            echo "[SKIP] $cmd — " . $e->getMessage() . "\n";
        }
    }

    // 2) Run migrations
    try {
        Artisan::call('migrate', ['--force' => true]);
        echo "\n[OK] migrate\n" . Artisan::output() . "\n";
    } catch (\Throwable $e) {
        echo "[FAIL] migrate — " . $e->getMessage() . "\n";
    }

    // 3) Storage link (safe if exists)
    try {
        if (!file_exists($base . '/public/storage')) {
            Artisan::call('storage:link');
            echo "[OK] storage:link\n";
        } else {
            echo "[OK] storage link already exists\n";
        }
    } catch (\Throwable $e) {
        echo "[SKIP] storage:link — " . $e->getMessage() . "\n";
    }

    // 4) Rebuild caches
    foreach (['config:cache', 'route:cache', 'view:cache'] as $cmd) {
        try {
            Artisan::call($cmd);
            echo "[OK] $cmd\n";
        } catch (\Throwable $e) {
            echo "[SKIP] $cmd — " . $e->getMessage() . "\n";
        }
    }

    echo "\n===================\nDONE. Now DELETE public/fix-deploy.php from the server!\n";
} catch (\Throwable $e) {
    echo "FATAL BOOT ERROR: " . $e->getMessage() . "\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n";
    echo "\nIf composer autoload is stale, also upload any new app/ files and re-run.\n";
}
