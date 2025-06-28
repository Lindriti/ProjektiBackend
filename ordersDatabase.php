<?php
require_once 'config.php'; // supozojmë që $conn është objekti PDO

try {
    // Create bread table
    $sql = "CREATE TABLE IF NOT EXISTS bread (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table 'bread' created successfully.<br>";

    // Create orders table
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        bread_id INT NOT NULL,
        order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (bread_id) REFERENCES bread(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    $conn->exec($sql);
    echo "Table 'orders' created successfully.<br>";

} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>
