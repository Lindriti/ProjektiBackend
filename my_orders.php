<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];

$sql = "
    SELECT 
        orders.id AS order_id,
        orders.order_date,
        bread.name AS bread_name,
        bread.price AS bread_price,
        bread.photo AS bread_photo
    FROM orders
    JOIN bread ON orders.bread_id = bread.id
    WHERE orders.user_id = :user_id
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .order-photo {
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h1">Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
            <div>
                <a href="user.php" class="btn btn-outline-light me-2">Shko te Profili</a>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">

        <?php if (empty($orders)): ?>
            <p class="text-center">No orders found.</p>
        <?php else: ?>
            <table class="table table-striped table-bordered w-75 mx-auto text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Food</th>
                        <th>Price (€)</th>
                        <th>Photo</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']) ?></td>
                            <td><?= htmlspecialchars($_SESSION['name'] ?? 'Unknown') ?></td>
                            <td><?= htmlspecialchars($order['bread_name']) ?></td>
                            <td><?= number_format($order['bread_price'], 2) ?></td>
                            <td>
                                <?php if (!empty($order['bread_photo']) && file_exists($order['bread_photo'])): ?>
                                    <img src="<?= htmlspecialchars($order['bread_photo']) ?>" alt="Bread photo" class="order-photo rounded">
                                <?php else: ?>
                                    No photo
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>