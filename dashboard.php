<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit;
}

$page = $_GET['page'] ?? 'users';
$message = "";

// USERS CRUD
if ($page === 'users') {
    if (isset($_GET['delete_user'])) {
        $deleteId = $_GET['delete_user'];
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $deleteId);
        $stmt->execute();
        $message = "User deleted successfully.";
    }

    if (isset($_POST['update_user'])) {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role_id = $_POST['role_id'];

        if ($name && $surname && $username && $email) {
            $sql = "UPDATE users SET name=:name, surname=:surname, username=:username, email=:email, role_id=:role_id WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":surname", $surname);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":role_id", $role_id);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $message = "User updated successfully.";
        } else {
            $message = "Please fill all fields for user update.";
        }
    }

    $editUser = null;
    if (isset($_GET['edit_user'])) {
        $id = $_GET['edit_user'];
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ORDERS delete
if ($page === 'orders' && isset($_GET['delete_order'])) {
    $deleteId = $_GET['delete_order'];
    $sql = "DELETE FROM orders WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $deleteId);
    $stmt->execute();
    $message = "The order was completed successfully.";
}

if ($page === 'orders') {
    $sql = "SELECT o.id, o.order_date, u.name AS user_name, 
                   b.name AS bread_name, b.photo AS bread_photo, b.price AS bread_price 
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN bread b ON o.bread_id = b.id
            ORDER BY o.order_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Food management
if ($page === 'bread') {
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $sql = "SELECT photo FROM bread WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $breadToDelete = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($breadToDelete && !empty($breadToDelete['photo']) && file_exists($breadToDelete['photo'])) {
            unlink($breadToDelete['photo']);
        }
        $sql = "DELETE FROM bread WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $message = "Items deleted.";
    }

    if (isset($_POST['add']) || isset($_POST['update'])) {
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $id = $_POST['id'] ?? null;

        if (!empty($name) && !empty($price)) {
            $photoPath = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $allowed = ['jpg','jpeg','png','gif'];
                $fileExt = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                if (in_array($fileExt, $allowed)) {
                    $newName = uniqid() . '.' . $fileExt;
                    $uploadDir = 'uploads/bread_photos/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $destination = $uploadDir . $newName;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                        $photoPath = $destination;
                    }
                }
            }

            if (isset($_POST['add'])) {
                $sql = "INSERT INTO bread (name, price, photo) VALUES (:name, :price, :photo)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":name", $name);
                $stmt->bindParam(":price", $price);
                $stmt->bindParam(":photo", $photoPath);
                $stmt->execute();
                $message = "Bread added successfully.";
            } elseif (isset($_POST['update']) && $id) {
                if ($photoPath) {
                    $sql = "SELECT photo FROM bread WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":id", $id);
                    $stmt->execute();
                    $oldBread = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($oldBread && !empty($oldBread['photo']) && file_exists($oldBread['photo'])) {
                        unlink($oldBread['photo']);
                    }
                    $sql = "UPDATE bread SET name = :name, price = :price, photo = :photo WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":photo", $photoPath);
                } else {
                    $sql = "UPDATE bread SET name = :name, price = :price WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                }
                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":name", $name);
                $stmt->bindParam(":price", $price);
                $stmt->execute();
                $message = "Bread updated.";
            }
        } else {
            $message = "Please fill all fields.";
        }
    }

    $sql = "SELECT * FROM bread";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $breads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $editBread = null;
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $sql = "SELECT * FROM bread WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $editBread = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body, html { height: 100%; margin: 0; }
        .sidebar { width: 280px; background-color: #343a40; color: white; height: 100vh; position: fixed; padding: 1rem; }
        .sidebar a { color: white; display: block; padding: 0.5rem 1rem; text-decoration: none; margin-bottom: 0.5rem; border-radius: 4px; }
        .sidebar a.active, .sidebar a:hover { background-color: #495057; }
        .content { margin-left: 280px; padding: 2rem; }
        img.bread-photo, img.order-photo { max-width: 100px; height: auto; border-radius: 5px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="dashboard.php?page=users" class="<?= $page==='users' ? 'active' : '' ?>">Users</a>
    <a href="dashboard.php?page=orders" class="<?= $page==='orders' ? 'active' : '' ?>">Order Management</a>
    <a href="dashboard.php?page=bread" class="<?= $page==='bread' ? 'active' : '' ?>">Food Management</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h1>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($page === 'users'): ?>
        <h2>Users</h2>
        <?php if ($editUser): ?>
            <form method="POST" class="mb-4">
                <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Name" value="<?= htmlspecialchars($editUser['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="surname" class="form-control" placeholder="Surname" value="<?= htmlspecialchars($editUser['surname']) ?>" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" value="<?= htmlspecialchars($editUser['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= htmlspecialchars($editUser['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <select name="role_id" class="form-select" required>
                        <option value="1" <?= $editUser['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                        <option value="2" <?= $editUser['role_id'] == 2 ? 'selected' : '' ?>>User</option>
                    </select>
                </div>
                <button type="submit" name="update_user" class="btn btn-primary">Update User</button>
                <a href="dashboard.php?page=users" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Surname</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['surname']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= $user['role_id'] == 1 ? 'Admin' : 'User' ?></td>
                        <td>
                            <a href="dashboard.php?page=users&edit_user=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="dashboard.php?page=users&delete_user=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($page === 'orders'): ?>
        <h2>Order Management</h2>
        <?php if (empty($orders)): ?>
            <p>No orders found.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Bread</th>
                        <th>Price (€)</th>
                        <th>Photo</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['user_name']) ?></td>
                            <td><?= htmlspecialchars($order['bread_name']) ?></td>
                            <td><?= number_format($order['bread_price'], 2) ?></td>
                            <td>
                                <?php if (!empty($order['bread_photo']) && file_exists($order['bread_photo'])): ?>
                                    <img src="<?= htmlspecialchars($order['bread_photo']) ?>" alt="Bread photo" class="order-photo" />
                                <?php else: ?>
                                    No photo
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($order['order_date']) ?></td>
                            <td>
                                <a href="dashboard.php?page=orders&delete_order=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Mark order as completed?')">Order ready!</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php elseif ($page === 'bread'): ?>
        <form method="POST" enctype="multipart/form-data" class="mb-5">
            <input type="hidden" name="id" value="<?= $editBread ? $editBread['id'] : '' ?>">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Food name" value="<?= $editBread ? htmlspecialchars($editBread['name']) : '' ?>" required>
            </div>
            <div class="mb-3">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price (€)" value="<?= $editBread ? $editBread['price'] : '' ?>" required>
            </div>
            <div class="mb-3">
                <label>Photo (JPG, PNG, GIF):</label>
                <input type="file" name="photo" <?= $editBread ? '' : 'required' ?> accept="image/*" class="form-control" >
                <?php if ($editBread && !empty($editBread['photo'])): ?>
                    <img src="<?= htmlspecialchars($editBread['photo']) ?>" class="bread-photo mt-2" alt="Bread photo">
                <?php endif; ?>
            </div>
            <button type="submit" name="<?= $editBread ? 'update' : 'add' ?>" class="btn btn-<?= $editBread ? 'warning' : 'primary' ?>">
                <?= $editBread ? 'Update Food' : 'Add Food' ?>
            </button>
            <?php if ($editBread): ?>
                <a href="dashboard.php?page=bread" class="btn btn-secondary ms-2">Cancel</a>
            <?php endif; ?>
        </form>

        <h3>All Foods
        </h3>
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Food name</th><th>Price (€)</th><th>Photo</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($breads as $bread): ?>
                    <tr>
                        <td><?= $bread['id'] ?></td>
                        <td><?= htmlspecialchars($bread['name']) ?></td>
                        <td><?= number_format($bread['price'], 2) ?></td>
                        <td>
                            <?php if (!empty($bread['photo']) && file_exists($bread['photo'])): ?>
                                <img src="<?= htmlspecialchars($bread['photo']) ?>" class="bread-photo" alt="Bread photo" />
                            <?php else: ?>
                                No photo
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="dashboard.php?page=bread&edit=<?= $bread['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="dashboard.php?page=bread&delete=<?= $bread['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete bread?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Page not found.</p>
    <?php endif; ?>
</div>

</body>
</html>
