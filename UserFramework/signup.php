<?php require_once("UserFramework.php"); $signup = new UserAuth(); require_once ("header.php"); ?>
<?php
    if (isset($_POST["email"]) && ($_POST["password"])) {
        if ($_POST["email"] === $_POST["confirmEmail"]) {
            if ($_POST["password"] === $_POST["confirmPassword"]) {
                $signup->signup($_POST["firstname"], $_POST["lastname"], $_POST["username"], $_POST["email"], $_POST["confirmEmail"], $_POST["password"], $_POST["confirmPassword"]);
            } else {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-danger\" role=\"alert\">Passwords must match!</div>
            </div>");
            }
        } else {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-danger\" role=\"alert\">Emails must match!</div>
            </div>");
            }
        }
?>
    <div class="container my-2">
<?php if (isset($_SESSION["id"])): ?>
    <div class="container my-2">
        <div class="alert alert-primary" role="alert">You are currently logged in!</div>
        <a href="account.php" class="btn btn-info w-100 mx-auto my-2">Account Page</a>
        <h4>Greetings <?php echo $login->getInformation("username", $_SESSION["id"])?>!</h4>
    </div>
<?php else : ?>
    <div class="container">
        <h1>Signup</h1>
        <form method="post" id="signup">
            <div class="row">
                <div class="form-group col py-2">
                    <label for="firstname">First Name</label>
                    <input class="form-control" type="text" name="firstname" placeholder="John" id="firstname" required>
                </div>
                <div class="form-group col py-2">
                    <label for="lastname">Last Name</label>
                    <input class="form-control" type="text" name="lastname" placeholder="Doe" id="lastname" required>
                </div>
            </div>
            <div class="form-group py-2">
                <label for="username"><i class="fas fa-user"></i>&nbsp;Username</label>
                <input class="form-control" type="text" name="username" placeholder="MyUsername" id="username" required>
                <div id="usernameHelpBlock" class="form-text">
                    <ul>
                        <li>No Special Characters</li>
                        <li>4-32 Characters</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="form-group col py-2">
                    <label for="email"><i class="fas fa-envelope-open"></i>&nbsp;Email Address</label>
                    <input class="form-control" type="email" name="email" placeholder="Email@Domain.com" id="email" required>
                </div>
                <div class="form-group col py-2">
                    <label for="confirmEmail"><i class="fas fa-envelope"></i>&nbsp;Confirm Email Address</label>
                    <input class="form-control" type="email" name="confirmEmail" placeholder="Email@Domain.com" id="confirmEmail" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col py-2">
                    <label for="password"><i class="fas fa-unlock"></i>&nbsp;Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Password" id="password" required>
                </div>
                <div class="form-group col py-2">
                    <label for="confirmPassword"><i class="fas fa-lock"></i>&nbsp;Confirm Password</label>
                    <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm Password" id="confirmPassword" required>
                </div>
            </div>
            <div id="passwordHelpBlock" class="form-text">
                <ul>
                    <li>Special Characters Allowed</li>
                    <li>Must be 8-64 Characters</li>
                </ul>
            </div>
            <input type="submit" name="Signup" value="Signup" class="btn btn-info d-block my-5 w-100 mx-auto">
        </form>
    </div>
<?php endif; ?>
</body>
</html>