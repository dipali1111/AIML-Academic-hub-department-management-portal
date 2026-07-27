<?php
// Database configuration (PDO)
// Credentials are read from environment variables with safe local defaults.
// For production, set DB_HOST, DB_NAME, DB_USER, DB_PASS (e.g. in your web server env).
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'aiml_academichub';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

<<<<<<< HEAD
=======
$pdo = null;

>>>>>>> 45e2ed514fc6a290a4cb5ac7331c5ed0f6b52cd7
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
<<<<<<< HEAD
    die('Database connection failed. Please try again later.');
=======
    $pdo = null;
>>>>>>> 45e2ed514fc6a290a4cb5ac7331c5ed0f6b52cd7
}

// Site base URL helper (deployment-aware)
function base_url() {
    if (!empty($_SERVER['HTTP_HOST'])) {
        $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $base = preg_replace('#/modules$#', '', $script);
        return rtrim($base, '/');
    }
    return '/aiml_academichub';
}
