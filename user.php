<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];
$message = "";

if (isset($_POST['order'])) {
    $bread_id = $_POST['bread_id'];
    $sql = "INSERT INTO orders (user_id, bread_id) VALUES (:user_id, :bread_id)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':bread_id', $bread_id);
    if ($stmt->execute()) {
        $message = "Order placed successfully!";
    } else {
        $message = "Order failed. Try again.";
    }
}

// Merr të gjitha bukët me foto
$sql = "SELECT * FROM bread";
$stmt = $conn->prepare($sql);
$stmt->execute();
$breads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Buy Bread</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    img.bread-photo { max-width: 150px; height: auto; border-radius: 5px; }
</style>
</head>
<body>

<!-- Navbar me logout dhe my orders si butona -->
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="navbar-brand mb-0 h1">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
        <div>
            <a href="my_orders.php" class="btn btn-outline-light me-2">My Orders</a>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($breads as $bread): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <?php if (!empty($bread['photo']) && file_exists($bread['photo'])): ?>
                        <img src="<?= htmlspecialchars($bread['photo']) ?>" class="card-img-top bread-photo" alt="Bread photo">
                    <?php else: ?>
                        <div class="card-img-top bread-photo d-flex align-items-center justify-content-center bg-secondary text-white" style="height:150px;">
                            No photo
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($bread['name']) ?></h5>
                        <p class="card-text">Price: <?= number_format($bread['price'], 2) ?> €</p>
                        <form method="POST">
                            <input type="hidden" name="bread_id" value="<?= $bread['id'] ?>">
                            <button type="submit" name="order" class="btn btn-primary">Order</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
