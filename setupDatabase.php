<?php
// setup_database.php

// Parametrat e lidhjes me serverin MySQL
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db";

try {
    // Lidhja me serverin MySQL (pa database fillimisht)
    $conn = new PDO("mysql:host=$host", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Krijo database nëse nuk ekziston
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' created or already exists.<br>";

    // Lidhja me databazën e krijuar
    $conn->exec("USE `$dbname`");

    // Krijo tabela

    // 1. roles
    $sql = "CREATE TABLE IF NOT EXISTS roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role_name VARCHAR(50) NOT NULL UNIQUE
    ) ENGINE=InnoDB;";
    $conn->exec($sql);
    echo "Table 'roles' created.<br>";

    // Shto role bazë
    $sql = "INSERT IGNORE INTO roles (id, role_name) VALUES
        (1, 'Admin'),
        (2, 'User')";
    $conn->exec($sql);
    echo "Basic roles inserted.<br>";

    // 2. users
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        surname VARCHAR(100) NOT NULL,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role_id INT NOT NULL DEFAULT 2,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (role_id) REFERENCES roles(id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
    ) ENGINE=InnoDB;";
    $conn->exec($sql);
    echo "Table 'users' created.<br>";

    // 3. uploads
    $sql = "CREATE TABLE IF NOT EXISTS uploads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        file_name VARCHAR(255) NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
    ) ENGINE=InnoDB;";
    $conn->exec($sql);
    echo "Table 'uploads' created.<br>";

    echo "<br>Database setup completed successfully!";

} catch (PDOException $e) {
    die("Error in database setup: " . $e->getMessage());
}
?>
