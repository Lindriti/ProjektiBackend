<?php

include_once 'config.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $surname = $_POST['name'];
    $email = $_POST['email'];
    $tempPassword = $_POST['password'];
    $password = password_hash($tempPassword, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)";
    $sqlQuery = $conn->prepare($sql);

    $sqlQuery->bindParam(":name", $name);
    $sqlQuery->bindParam(":surname", $surname);
    $sqlQuery->bindParam(":email", $email);
    $sqlQuery->bindParam(":password", $password);
    
    $sqlQuery->execute();

    echo "Data saved successfully! <br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add user</title>
</head>
<body>
    <form action="add.php" method="POST">
        <input type="text" name="name" placeholder="Name"><br>
        <input type="text" name="surname" placeholder="Surname"><br>
        <input type="email" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="password"><br>
        <input type="submit" name="submit"/>
    </form>
</body>
</html>