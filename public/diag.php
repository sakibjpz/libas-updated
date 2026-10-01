<?php
/**
 * Diagnostics — catches the REAL production 500 error.
 * URL: https://libasbd.com/diag.php?key=libas-fix-9281  →  DELETE after use!
 */

if (($_GET['key'] ?? '') !== 'libas-fix-9281') { http_response_code(403); exit('Forbidden.'); }
header('Content-Type: text/plain; charset=utf-8');

$base = dirname(__DIR__);
echo "=== ENV ===\nPHP: " . PHP_VERSION . "\n";
echo "uploads writable: " . (is_writable($base . '/public/products-images') ? 'yes' : 'NO') . "\n";
echo "storage writable: " . (is_writable($base . '/storage/logs') ? 'yes' : 'NO') . "\n";

echo "\n=== LAST ERROR MESSAGES ===\n";
$log = $base . '/storage/logs/laravel.log';
if (file_exists($log)) {
    $lines = file($log);
    $errs = array_values(array_filter($lines, fn($l) => str_contains($l, '.ERROR')));
    echo implode("\n---\n", array_slice($errs, -6));
}

echo "\n=== SIMULATE HTTP / ===\n";
try {
    require $base . '/vendor/autoload.php';
    $app = require $base . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::create('/', 'GET');
    $response = $kernel->handle($request);
    echo "STATUS: " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 400) {
        echo substr($response->getContent(), 0, 1500);
    }
} catch (\Throwable $e) {
    echo "REAL ERROR:\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
}
echo "\n\nDELETE THIS FILE!";
