<?php
$pdo = new PDO('mysql:host=localhost;dbname=fittrack_db;charset=utf8mb4', 'root', '');

// Add new columns to goals table
$goalCols = [
    "ALTER TABLE goals ADD COLUMN goal_type VARCHAR(50) DEFAULT 'maintenance' AFTER user_id",
    "ALTER TABLE goals ADD COLUMN target_workouts_per_week INT DEFAULT 3 AFTER target_calories",
    "ALTER TABLE goals ADD COLUMN notes TEXT DEFAULT NULL AFTER target_workouts_per_week",
];
foreach ($goalCols as $sql) {
    try { $pdo->exec($sql); echo "OK: $sql\n"; }
    catch (PDOException $e) { echo "SKIP (exists): " . $e->getMessage() . "\n"; }
}

echo "\nMigration complete.\n";
