<?php
include_once 'config.php';
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $tempPassword = $_POST['password'];

    if(empty($name) || empty($surname) || empty($username) || empty($email) || empty($tempPassword)){
        echo "Please fill all fields.";
        header("refresh:3; url=add.php");
        exit;
    }

    $sqlCheck = "SELECT id FROM users WHERE username = :username";
    $sqlPrep = $conn->prepare($sqlCheck);
    $sqlPrep->bindParam(":username", $username);
    $sqlPrep->execute();

    if($sqlPrep->rowCount() > 0){
        echo "Username already exists. Choose another.";
        exit;
    }

    $password = password_hash($tempPassword, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, surname, username, email, password, role_id) 
            VALUES (:name, :surname, :username, :email, :password, 1)";
    $sqlQuery = $conn->prepare($sql);

    $sqlQuery->bindParam(":name", $name);
    $sqlQuery->bindParam(":surname", $surname);
    $sqlQuery->bindParam(":username", $username);
    $sqlQuery->bindParam(":email", $email);
    $sqlQuery->bindParam(":password", $password);

    $sqlQuery->execute();

    echo "Data saved successfully! <br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add user</title>
</head>
<body>
    <form action="add.php" method="POST">
        <input type="text" name="name" placeholder="Name"><br>
        <input type="text" name="surname" placeholder="Surname"><br>
        <input type="text" name="username" placeholder="Username"><br>
        <input type="email" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <input type="submit" name="submit" value="Add User" />
    </form>
</body>
</html>
