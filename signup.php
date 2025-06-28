<?php include_once 'header.php'; ?>
<body>
    <div class="container">
        <div class="w-50 mx-auto" style="margin: 100px auto;">
            <h1 class="mb-5">Register</h1>

            <form action="signupLogic.php" method="POST">
                <div class="form-outline mb-4">
                    <input type="text" class="form-control" name="name" required />
                    <label class="form-label">Name</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="text" class="form-control" name="surname" required />
                    <label class="form-label">Surname</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="text" class="form-control" name="username" required />
                    <label class="form-label">Username</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="email" class="form-control" name="email" required />
                    <label class="form-label">Email</label>
                </div>

                <div class="form-outline mb-4">
                    <input type="password" class="form-control" name="password" required />
                    <label class="form-label">Password</label>
                </div>

                <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">Register</button>

                <div class="text-center">
                    <p>Already have an account? <a href="login.php">Sign In</a></p>
                </div>
            </form>
        </div>
    </div>
<?php include_once 'footer.php'; ?>
