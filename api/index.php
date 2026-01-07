<?php

// Aktifkan pelaporan error lengkap untuk debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__ . '/../public/index.php';
} catch (\Exception $e) {
    echo "<h1>Laravel Boot Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
