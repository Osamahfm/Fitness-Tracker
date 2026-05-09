<?php
$pdo = new PDO('mysql:host=localhost;dbname=fittrack_db;charset=utf8mb4', 'root', '');
// Add distance_km to workouts table
try {
    $pdo->exec('ALTER TABLE workouts ADD COLUMN distance_km DECIMAL(6,2) DEFAULT NULL AFTER duration');
    echo 'Column distance_km added to workouts' . PHP_EOL;
} catch(PDOException $e) {
    echo 'distance_km already exists (OK)' . PHP_EOL;
}
// Add weight_kg to users table
try {
    $pdo->exec('ALTER TABLE users ADD COLUMN weight_kg DECIMAL(5,2) DEFAULT 70.00 AFTER password_hash');
    echo 'Column weight_kg added to users' . PHP_EOL;
} catch(PDOException $e) {
    echo 'weight_kg already exists (OK)' . PHP_EOL;
}
echo 'DB migration complete.' . PHP_EOL;
