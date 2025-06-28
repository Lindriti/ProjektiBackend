<?php
session_start();
require_once 'config.php';

if (isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $surname = trim($_POST['surname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tempPassword = $_POST['password'] ?? '';

    if (empty($name) || empty($surname) || empty($username) || empty($email) || empty($tempPassword)) {
        echo "Please fill in all fields.";
        header("refresh:3; url=signup.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address.";
        header("refresh:3; url=signup.php");
        exit;
    }

    $checkSql = "SELECT username, email FROM users WHERE username = :username OR email = :email";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bindParam(":username", $username);
    $checkStmt->bindParam(":email", $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);
        if ($existing['username'] === $username) {
            echo "Username already exists.";
        } elseif ($existing['email'] === $email) {
            echo "Email is already registered.";
        } else {
            echo "Username or email already exists.";
        }
        header("refresh:3; url=signup.php");
        exit;
    }

    $password = password_hash($tempPassword, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, surname, username, email, password) VALUES (:name, :surname, :username, :email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":surname", $surname);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);

    if ($stmt->execute()) {
        echo "Registration successful! Redirecting to login...";
        header("refresh:2; url=login.php");
        exit;
    } else {
        echo "An error occurred during registration. Please try again.";
        header("refresh:3; url=signup.php");
        exit;
    }
} else {
    header("Location: signup.php");
    exit;
}
?>
