<?php require_once("UserFramework.php"); $login = new UserAuth(); require_once ("header.php"); ?>

<?php if (isset($_POST["email"], $_POST["password"])) :?>
<?php
    $login->login($_POST["email"], $_POST["password"]);
?> 
<?php endif; ?>
<?php if (isset($_POST["logout"]) && (!empty($_SESSION["id"]))) {
    $login->LogOut();
} ?>
<?php if (isset($_SESSION["id"])): ?>
    <div class="container my-2">
        <div class="alert alert-primary" role="alert">You are currently logged in!</div>
        <a href="index.php" class="btn btn-primary w-100 mx-auto my-2">Back to Home</a>
        <a href="account.php" class="btn btn-info w-100 mx-auto my-2">Account Page</a>
        <h4>Greetings <?php echo $login->getInformation("first", $_SESSION["id"])?>!</h4>
        <p>Looking to logout? Click below!</p>
        <form method="post">
            <input type="submit" value="Logout" name="logout" class="btn btn-danger">
        </form>
    </div>
<?php else: ?>
    <div class="container">
        <h1>Login</h1>
        <form method="post">
            <div class="form-group py-2">
                <label for="username"><i class="fas fa-envelope"></i>&nbsp;Email Address</label>
                <input class="form-control" type="email" name="email" placeholder="Email@Domain.com" id="email" required>
            </div>
            <div class="form-group py-2">
                <label for="password"><i class="fas fa-lock"></i>&nbsp;Password</label>
                <input class="form-control" type="password" name="password" placeholder="Password" id="password" required>
            </div>
            <input type="submit" name="Login" value="Login" class="btn btn-success">
        </form>
        <div class="row">
            <div class="col">
            <a href="index.php" class="btn btn-primary w-100 mx-auto my-4">Back to Home</a>
            </div>
            <div class="col">
            <a href="signup.php" class="btn btn-info w-100 mx-auto my-4">Signup</a>
            </div>
        </div>
        
    </div>
<?php endif; ?>
</body>
</html>