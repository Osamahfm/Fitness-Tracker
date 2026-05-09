<?php
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
    echo "MySQL connection OK\n";
    $pdo->exec('CREATE DATABASE IF NOT EXISTS fittrack_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "Database fittrack_db created/verified OK\n";

    $pdo->exec('USE fittrack_db');

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "Table users OK\n";

    $pdo->exec("CREATE TABLE IF NOT EXISTS workouts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        type VARCHAR(100) NOT NULL,
        duration INT NOT NULL,
        calories_burned INT DEFAULT 0,
        workout_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Table workouts OK\n";

    $pdo->exec("CREATE TABLE IF NOT EXISTS meals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        food_name VARCHAR(255) NOT NULL,
        calories INT NOT NULL,
        protein DECIMAL(5,2) DEFAULT 0.00,
        carbs DECIMAL(5,2) DEFAULT 0.00,
        fats DECIMAL(5,2) DEFAULT 0.00,
        logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Table meals OK\n";

    $pdo->exec("CREATE TABLE IF NOT EXISTS goals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        target_weight DECIMAL(5,2) NOT NULL,
        target_calories INT NOT NULL,
        status ENUM('active','achieved','abandoned') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Table goals OK\n";

    echo "\nAll done! Database and tables are ready.\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nPossible fixes:\n";
    echo "1. Make sure XAMPP MySQL is running (check XAMPP Control Panel)\n";
    echo "2. Check your MySQL root password in config/config.php\n";
}
