<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    echo "Please fill in all fields.";
    header("refresh:3; url=login.php");
    exit;
}

$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() == 1) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $user['password'])) {
        // Save user info in session
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role_id'] = $user['role_id'];

        // Redirect based on role
        if ($user['role_id'] == 1) {
            header("Location: dashboard.php");
        } else {
            header("Location: user.php");
        }
        exit;
    } else {
        echo "Incorrect password.";
        header("refresh:3; url=login.php");
        exit;
    }
} else {
    echo "User not found.";
    header("refresh:3; url=login.php");
    exit;
}
?>
