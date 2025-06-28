<?php
session_start();
require_once 'config.php';

// Vetëm adminët lejohen
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit;
}

$message = "";

// Shto bukë të re
if (isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($price)) {
        $sql = "INSERT INTO bread (name, price) VALUES (:name, :price)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":price", $price);
        $stmt->execute();
        $message = "Bread added successfully.";
    } else {
        $message = "Please fill all fields.";
    }
}

// Fshi bukën
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM bread WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $message = "Bread deleted.";
}

// Përditëso bukën
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($price)) {
        $sql = "UPDATE bread SET name = :name, price = :price WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":price", $price);
        $stmt->execute();
        $message = "Bread updated.";
    } else {
        $message = "Please fill all fields.";
    }
}

// Merr të gjitha bukët
$sql = "SELECT * FROM bread";
$stmt = $conn->prepare($sql);
$stmt->execute();
$breads = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nëse po editojmë
$editBread = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM bread WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $editBread = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bread</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h1 class="mb-4">Admin Bread Management</h1>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-5">
        <input type="hidden" name="id" value="<?= $editBread ? $editBread['id'] : '' ?>">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Bread name"
                   value="<?= $editBread ? htmlspecialchars($editBread['name']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <input type="number" step="0.01" name="price" class="form-control" placeholder="Price (€)"
                   value="<?= $editBread ? $editBread['price'] : '' ?>" required>
        </div>
        <button type="submit" name="<?= $editBread ? 'update' : 'add' ?>" class="btn btn-<?= $editBread ? 'warning' : 'primary' ?>">
            <?= $editBread ? 'Update Bread' : 'Add Bread' ?>
        </button>
        <?php if ($editBread): ?>
            <a href="admin_bread.php" class="btn btn-secondary ms-2">Cancel</a>
        <?php endif; ?>
    </form>

    <h3>All Breads</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bread Name</th>
                <th>Price (€)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($breads as $bread): ?>
                <tr>
                    <td><?= $bread['id'] ?></td>
                    <td><?= htmlspecialchars($bread['name']) ?></td>
                    <td><?= number_format($bread['price'], 2) ?></td>
                    <td>
                        <a href="?edit=<?= $bread['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?= $bread['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
