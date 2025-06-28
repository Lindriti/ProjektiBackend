<?php
require_once 'config.php';

$id = $_POST['id'];
$name = $_POST['name'];
$surname = $_POST['surname'];
$username = $_POST['username'];
$email = $_POST['email'];

if (empty($id) || empty($name) || empty($surname) || empty($username) || empty($email)) {
    echo "Please fill all required fields.";
    exit;
}

$sql = "UPDATE users SET name=:name, surname=:surname, username=:username, email=:email WHERE id=:id";

$prep = $conn->prepare($sql);

$prep->bindParam(":id", $id);
$prep->bindParam(":name", $name);
$prep->bindParam(":surname", $surname);
$prep->bindParam(":username", $username);
$prep->bindParam(":email", $email);

$prep->execute();

header("Location: dashboard.php");
exit;
