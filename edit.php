<?php
include_once 'config.php';

$id = $_GET['id'];

$sql = "SELECT * FROM users WHERE id = :id";

$prep = $conn->prepare($sql);
$prep->bindParam(":id", $id);
$prep->execute();

$data = $prep->fetch();

if (!$data) {
    echo "User not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit User</title>
    <style>
        form > input {
            margin-bottom: 10px;
            font-size: 20px;
            padding: 5px;
            width: 100%;
            max-width: 400px;
        }
        button, input[type="submit"] {
            background: none;
            border: solid 1px black;
            padding: 10px 40px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="update.php" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">

    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" placeholder="Enter name" value="<?php echo htmlspecialchars($data['name']); ?>"><br><br>

    <label for="surname">Surname:</label><br>
    <input type="text" id="surname" name="surname" placeholder="Enter surname" value="<?php echo htmlspecialchars($data['surname']); ?>"><br><br>

    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" placeholder="Enter username" value="<?php echo htmlspecialchars($data['username']); ?>"><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" placeholder="Enter email" value="<?php echo htmlspecialchars($data['email']); ?>"><br><br>

    <input type="submit" name="submit" value="Update">
</form>
</body>
</html>
