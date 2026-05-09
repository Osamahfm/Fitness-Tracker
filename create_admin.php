<?php
$pdo = new PDO('mysql:host=localhost;dbname=fittrack_db;charset=utf8mb4', 'root', '');
try { 
    $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user' AFTER email"); 
    echo "Role column added.\n"; 
} catch (Exception $e) { 
    echo "Role column might already exist: " . $e->getMessage() . "\n"; 
} 
$pwd = password_hash('admin123', PASSWORD_BCRYPT); 
$stmt = $pdo->prepare('INSERT INTO users (name, email, role, password_hash) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE role="admin", password_hash=?'); 
$stmt->execute(['Admin User', 'admin@fittrack.com', 'admin', $pwd, $pwd]); 
echo "Admin user created/updated: admin@fittrack.com / admin123\n";
