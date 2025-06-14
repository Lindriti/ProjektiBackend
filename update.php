<?php

    require_once 'config.php';

    $id = $_POST['id'];
    $name =  $_POST['name'];
    $surname =  $_POST['surname'];
    $email =  $_POST['email'];
 

    $sql = "UPDATE users SET name=:name, surname=:surname, email=:email WHERE id=:id";

    $prep = $conn->prepare($sql);

    $prep->bindParam(":id", $id);
    $prep->bindParam(":name", $name);
    $prep->bindParam(":surname", $surname);
    $prep->bindParam(":email", $email);

    $prep->execute();

    header("Location:dashboard.php");
