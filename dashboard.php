<?php include_once 'header.php';?>
<body>
    <?php

    include_once 'config.php';
    $sql = "SELECT * FROM users";
    $getUsers = $conn->prepare($sql);
    $getUsers->execute();

    $users = $getUsers->fetchAll();
    if(!isset($_SESSION['name'])) {
        header("Location: login.php");
    }
    ?>

    <div class="d-flex" style="height: 100vh;">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-4">Sidebar</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" aria-current="page">
                        <svg class="bi me-2" width="16" height="16">
                            <use xlink:href="#home"></use>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link active">
                        <svg class="bi me-2" width="16" height="16">
                            <use xlink:href="#speedometer2"></use>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16">
                            <use xlink:href="#table"></use>
                        </svg>
                        Orders
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16">
                            <use xlink:href="#grid"></use>
                        </svg>
                        Products
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16">
                            <use xlink:href="#people-circle"></use>
                        </svg>
                        Customers
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16">
                            <use xlink:href="#people-circle"></use>
                        </svg>
                        Logout
                    </a>
                </li>
            </ul>
            <hr>
        </div>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">

            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">New project...</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
        </div>
        <div class="w-100 h-100 p-5">
            <h1><?php echo"Welcome " . $_SESSION['name'] ?></h1>
            <table style="heigth: min-content;">
                <thead>
                    <th>ID</ih>
                    <th>Name</ih>
                    <th>Surname</ih>
                    <th>Email</ih>
                </thead>
                <tbody>
                    <?php
                    foreach ($users as $user) {
                    ?>
                        <tr>
                            <td><?php echo $user['id'] ?></td>
                            <td><?php echo $user['name'] ?></td>
                            <td><?php echo $user['surname'] ?></td>
                            <td><?php echo $user['email'] ?></td>
                            <td><a href="delete.php?id=<?php echo $user['id'] ?>">Delete</a> | <a href="edit.php?id=<?php echo $user['id'] ?>">Update</a></td>
                        </tr>
                    <?php
                    }

                    ?>
                </tbody>
            </table>

        </div>
    </div>
    <?php include_once 'footer.php';?>