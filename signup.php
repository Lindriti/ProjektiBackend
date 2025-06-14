<?php include_once 'header.php';?>
<body>
    <div class="container">
        <div class="w-50 mx-auto" style="margin: 100px auto;">
            <h1 class="mb-5">Please sign in</h1>

<form action="signupLogic.php" method="POST">

  <!-- Name input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="form2Example3" class="form-control" name = "name"/>
    <label class="form-label" for="form2Example3">Name</label>
  </div>

  <!-- Surname input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="form2Example2" class="form-control" name = "surname"/>
    <label class="form-label" for="form2Example2">Surname</label>
  </div>

  <!-- Username input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="form2Example2" class="form-control" name = "username"/>
    <label class="form-label" for="form2Example2">Username</label>
  </div>

  <!-- Email input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="email" id="form2Example1" class="form-control" name = "email"/>
    <label class="form-label" for="form2Example1">Email address</label>
  </div>

  <!-- Password input -->
  <div data-mdb-input-init class="form-outline mb-4">
    <input type="password" id="form2Example2" class="form-control" />
    <label class="form-label" for="form2Example2">Password</label>
  </div>

  <!-- Submit button -->
  <button  type="submit" name="sumit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>

  <!-- Register buttons -->
  <div class="text-center">
    <p>Have an account? <a href="login.php">Sign In</a></p>
  </div>
  </form>
  </div>
  </div>
<?php include_once 'footer.php';?>