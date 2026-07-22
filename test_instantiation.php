<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Enable full error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

use App\Http\Controllers\Web\UsersController;
echo "Calling static middleware method...\n";
try {
    $x = UsersController::middleware();
    echo "Success!\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
