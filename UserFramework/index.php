<?php require_once ("header.php"); ?>
    <div class="container my-5">
        <a href="login.php" class="btn d-block btn-primary">Login Here</a>
        <h2>Hello there<?php if (isset($_SESSION["id"])) {$login = new UserAuth();echo " ".$login->getInformation("username", $_SESSION["id"]);} ?>.</h2><?php $ConfigTable = new UFDBTable("config"); echo($ConfigTable->PrintResults()); ?>
    </div>
</body>
</html>