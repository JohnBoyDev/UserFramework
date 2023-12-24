<?php require_once("UserFramework.php"); $login = new UserAuth(); require_once ("header.php"); ?>
<?php if (!isset($_SESSION["id"])) {
    header("Location: login.php");
} ?>

<?php if (isset($_POST["email"]) && ($_POST["currentPassword"])) {
    if (!empty($_POST["password"]) && !empty($_POST["passwordConfirm"])) {
        $login->editAccount($_SESSION["id"], $_POST["email"], $_POST["username"], $_POST["currentPassword"], true, $_POST["password"], $_POST["passwordConfirm"]);
    } else {
        $login->editAccount($_SESSION["id"], $_POST["email"], $_POST["username"], $_POST["currentPassword"]);
    }
} ?>

<?php if (isset($_POST["logout"]) && (!empty($_SESSION["id"]))) {
    $login->LogOut();
} ?>

<div class="container">
    <div class="row">
        <div class="col-8">
            <h4>Account Information: <?php echo $login->getInformation("username", $_SESSION["id"])?></h4>
            <form class="row g-3 needs-validation" method="POST">
                <div class="mb-3">
                    <label for="email"><i class="fas fa-envelope"></i>&nbsp;Email Address</label>
                    <input type="email" class="form-control" name="email" value="<?php echo($login->getInformation("email", $_SESSION["id"]));?>" required>
                </div>
                <div class="mb-3">
                    <label for="username"><i class="fa-solid fa-address-card"></i></i>&nbsp;Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo($login->getInformation("username", $_SESSION["id"]));?>" required>
                </div>
                <div class="mb-3">
                    <label for="currentPassword" class="form-label"><i class="fa-solid fa-key"></i>&nbsp;Current Password</label>
                    <input type="password" class="form-control" name="currentPassword" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><i class="fa-solid fa-lock"></i>&nbsp;Password</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="mb-3">
                    <label for="passwordConfirm" class="form-label"><i class="fa-solid fa-lock"></i>&nbsp;Confirm Password</label>
                    <input type="password" class="form-control" name="passwordConfirm">
                </div>
                <div class="mb-3">
                    <label for="dateCreated" class="form-label"><i class="fa-solid fa-calendar-day"></i>&nbsp;Date Account Created</label>
                    <input type="date" class="form-control" name="dateCreated" value="<?php $date=date_create($login->getInformation("created", $_SESSION["id"])); echo date_format($date,"Y-m-d");?>" disabled>
                </div>
                <button type="submit" class="btn btn-primary">Edit</button>
            </form>
        </div>
        <div class="col-4">
            <h4 class="text-center">Account Actions</h4>
            <form method="post">
                <input type="submit" value="Logout" name="logout" class="btn btn-danger w-100">
            </form>
        </div>
    </div>
</div>