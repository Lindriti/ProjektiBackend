<?php
    include_once 'config.php';
    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id=:id";

    $prep = $conn->prepare($sql);
    $prep->bindParam(":id", $id);
   
    $prep->execute();

    $data = $prep->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        form>input {
            margin-bottom: 10px;
            font-size: 20px;
            padding: 5px;
        }
        button{
            background:none;
            border: solid 1px black;
            padding: 10px 40px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="update.php" method="POST">
        <input type="hidden" name="id" placeholder="Name" value="<?php echo $data['id'] ?>"><br>
        <input type="text" name="name" placeholder="Name" value="<?php echo $data['name'] ?>"><br>
        <input type="text" name="surname" placeholder="surname" value="<?php echo $data['surname'] ?>"><br>
        <input type="email" name="email" placeholder="Email" value="<?php echo $data['email'] ?>"><br>
        <input type="submit" name="submit"></input>
    </form>
</body>
</html>