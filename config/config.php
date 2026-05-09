<?php
// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// URL Root (Adjust if running on a different port or path)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('URLROOT', $protocol . '://' . $host);

// Site Name
define('SITENAME', 'FitTrack');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fittrack_db');
