<?php

// Aktifkan pelaporan error lengkap
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pastikan file jantung Laravel ada
$autoload = __DIR__ . '/../vendor/autoload.php';
$appFile = __DIR__ . '/../bootstrap/app.php';

if (!file_exists($autoload)) {
    die("Error: folder 'vendor' tidak terdeteksi di Vercel. Pastikan .gitignore sudah membolehkan folder vendor.");
}

require $autoload;

try {
    // Jalankan bootstrap Laravel lewat public/index.php
    require __DIR__ . '/../public/index.php';
} catch (\Exception $e) {
    echo "<h1>Debug Laravel di Vercel</h1>";
    echo "<b>Pesan Error:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " baris " . $e->getLine() . "<br>";
    echo "<h3>Stack Trace:</h3><pre>" . $e->getTraceAsString() . "</pre>";
}
