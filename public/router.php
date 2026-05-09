<?php
// router.php for PHP built-in server and IDE extensions (like PHP Server)
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|ico)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // Serve the requested resource as-is.
}

require_once __DIR__ . '/index.php';
