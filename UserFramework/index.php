<?php require_once ("header.php"); ?>
    <div class="px-4 bg-success-subtle text-dark">
        <div class="container py-5 mx-auto text-center">
            <h1 class="display-1">PHP UserFramework</h1>
            <p class="lead w-75 mx-auto">A PHP-based framework for building authentication designed in object-oriented programming. This framework is a learning experiment of safe, useful, and secure coding practices within PHP and Object-Oriented Programming.</p>
<?php if(isset($_SESSION["id"])) : ?>
            <a href="account.php" class="btn btn-info">Account</a>
<?php else: ?>
            <a href="login.php" class="btn btn-primary">Login Here</a>
<?php endif; ?>
            <a href="https://github.com/JohnBoyDev/UserFramework" class="btn btn-secondary" target="_blank" rel="noopener noreferrer">View on Github</a>
        </div>
    </div>
    <div class="container my-5">
<?php if (isset($_COOKIE["Logout"])): ?>
        <div class="alert alert-success" role="alert"><span>Logged Out</span></div>
        <?php setcookie('Logout', 'content', 1); ?>
<?php endif; ?>
<?php if(isset($_SESSION["id"])) : ?>
        <h2>Hello, <?php if (isset($_SESSION["id"])) {$login = new UserAuth();echo " ".$login->getInformation("first", $_SESSION["id"]);} ?>.</h2>
<?php else: ?>
        <h2>Hello.</h2>
<?php endif; ?>
        <?php $ConfigTable = new UFDBTable("config"); echo($ConfigTable->PrintTable()); ?>
    </div>
</body>
</html>