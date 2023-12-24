<?php require_once("UserFramework.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP User Framework</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <a href="login.php" class="btn d-block btn-primary">Login Here</a>
        <h2>Hello there<?php if (isset($_SESSION["id"])) {$login = new UserAuth();echo " ".$login->getInformation("username", $_SESSION["id"]);} ?>.</h2><?php $ConfigTable = new UFDBTable("config"); echo($ConfigTable->PrintResults()); ?>
    </div>
</body>
</html>