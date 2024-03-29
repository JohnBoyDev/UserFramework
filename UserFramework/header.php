<?php require_once("UserFramework.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP User Framework</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
    <div class="container d-flex">
        <a class="navbar-brand" href="index.php">PHP User Framework</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav d-none d-lg-flex justify-content-end w-100">
            <a class="nav-link" aria-current="page" href="index.php">Home</a>
            <a class="nav-link" href="account.php"><?php if (isset($_SESSION["id"])) {$login = new UserAuth();echo "Account";} else {echo "Login";} ?></a>

        </div>
        
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav d-lg-none">
                <a class="nav-link d-block mx-auto" aria-current="page" href="index.php">Home</a>
                <a class="nav-link d-block mx-auto" href="account.php"><?php if (isset($_SESSION["id"])) {$login = new UserAuth();echo "Account";} else {echo "Login";} ?></a>
            </div>
        </div>
    </div>
</nav>