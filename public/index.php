<?php
/**
 * FitTrack - Front Controller
 */

// Load Configuration
require_once __DIR__ . '/../config/config.php';

// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload Core Classes
spl_autoload_register(function ($className) {
    $paths = [
        APPROOT . '/app/Core/',
        APPROOT . '/app/Controllers/',
        APPROOT . '/app/Models/'
    ];

    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Initialize Router
$router = new Router();
