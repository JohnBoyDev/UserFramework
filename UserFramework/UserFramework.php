<?php

/*

    A Framework based in PHP to make user creation and sessions quick to deploy.
    (c) 2023, John "JohnBoyDev" Channell

*/
session_start();

class UFDatabase extends PDO {
    protected $host;
    protected $name;
    protected $user;
    protected $pass;

    protected $UFDatabase;

    function __construct($host, $name, $user, $pass) {
        $this->host = $host; $this->name = $name; $this->user = $user; $this->pass = $pass;

        try {
            parent::__construct("mysql:host=".$this->host.";dbname=".$this->name.";charset=UTF8", $this->user, $this->pass);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\Throwable $th) {
            echo 'Caught exception: ',  $th->getMessage(), "\n";
        }
    }
}

// Establish DB connection

class UFDBTable extends UFDatabase {
    private $tableName;
    private $tableElements = array();

    protected $UFDatabase;

    function __construct($tableName) {
        $this->UFDatabase = new UFDatabase("localhost", "uf-test", "uf-database", "");

        $statement = $this->UFDatabase->prepare("SELECT * FROM ".$tableName);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $key => $value) {
            array_push($this->tableElements, $value);
        }
        
        $this->UFDatabase = null;
    }

    function PrintResults() {
        $res = "";
        foreach ($this->tableElements as $key => $value) {
            foreach ($value as $k => $v) {
                $res .= $v;
            } 
        }

        return $res;
    }

    function PrintTable() {
        $tableResults = "";
        foreach ($this->tableElements as $key => $value) {
            if ($key === 0) {
$tableResults .= "
<table class=\"table table-striped table-bordered table-hover text-center table-sm\">
    <thead class=\"table-dark\">
        <tr>";
                foreach ($value as $k => $v) {
                    $tableResults .= "
            <th>$k</th>";
                }
        
$tableResults .=    "
        </tr>
    </thead>
    <tbody>";
            }
            $tableResults .= "
        <tr>";
            $count = 0;
            foreach ($value as $k => $v) {
                if ($count === 0) {
                    $tableResults .= "<th scope=\"row\">$v</th>";
                } else {
                    $tableResults .= "<td>$v</td>";
                }
                $count++;
            }
            $tableResults .= "</tr>";
        }
$tableResults .= "
    </tbody>
</table>
";
        return $tableResults;
    }
}


class UserAuth extends UFDatabase {
    protected $UFDatabase;

    function __construct() {
        $this->UFDatabase = new UFDatabase("localhost", "uf-test", "uf-database", "");
    }

    function login($u, $pwd) {
        $email = htmlspecialchars($u);
        $pass = htmlspecialchars($pwd);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-danger\" role=\"alert\">BAD EMAIL</div>
            </div>");
            return(null);
        }
        if (isset($_SESSION["id"])) {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-info\" role=\"alert\">You're already logged in as ".$this->getInformation("username", $_SESSION["id"]).".</div>
            </div>");
            return(null);
        }

        $statement = $this->UFDatabase->prepare("SELECT User_ID, User_Email, User_Pass FROM accounts WHERE LOWER(User_Email) = ?");
        $statement->execute([strtolower(htmlspecialchars($email))]);
        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        if (!empty($statement->rowCount())) {
            if (password_verify($pass, $results["User_Pass"])) {
                $_SESSION["id"] = $results["User_ID"];
                header("Location: login.php");
                return(null);
            } else {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-danger\" role=\"alert\"><h4>Error!</h4>Email and Password do not match!</div>
            </div>");
            return(null);
            }
        } else {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-danger\" role=\"alert\"><h4>Sorry!</h4>We haven't found an account that matches.</div>
            </div>");
            return(null);
        }
    }

    function editAccount($id, $email, $user, $pwd, $updatingPass = false, $newPass = null, $confirmPass = null) {
        $userID = htmlspecialchars($id);
        $email = htmlspecialchars($email);
        $username = htmlspecialchars($user);
        $pass = htmlspecialchars($pwd);
        $pass1 = null;
        $pass2 = null;
        if ($newPass !== null) {
            $pass1 = htmlspecialchars($newPass);
        } if ($confirmPass !== null) {
            $pass2 = htmlspecialchars($confirmPass);
        }

        if (!isset($_SESSION["id"])) {
            return(null);
        }

        $passwordHash = null;

        $statement = $this->UFDatabase->prepare("SELECT User_Email, User_Pass FROM accounts WHERE User_ID = ?");
        $statement->execute([htmlspecialchars($userID)]);
        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        if (!empty($statement->rowCount())) {
            if (password_verify($pass, $results["User_Pass"])) {
                if($updatingPass) {
                    if ($pass1 == $pass2) {
                        $passwordHash = password_hash($pass1, PASSWORD_DEFAULT);
                        $statement = $this->UFDatabase->prepare("UPDATE accounts SET User_Email = ?, User_Name = ?, User_Pass = ? WHERE User_ID = ?");
                        $statement->execute([$email, $username, $passwordHash, $userID]);
                        echo("<div class=\"container my-2\">
                            <div class=\"alert alert-success\" role=\"alert\">Password has been updated.</div>
                        </div>");
                        return(null);
                    } else {
                        echo("<div class=\"container my-2\">
                            <div class=\"alert alert-danger\" role=\"alert\"><h4>Error!</h4>New passwords do not match!</div>
                        </div>");
                        return(null);
                    }
                } else {
                    $statement = $this->UFDatabase->prepare("UPDATE accounts SET User_Email = ?, User_Name = ? WHERE User_ID = ?");
                    $statement->execute([$email, $username, $userID]);
                    echo("<div class=\"container my-2\">
                        <div class=\"alert alert-success\" role=\"alert\">Information has been updated.</div>
                    </div>");
                    return(null);
                }
            } else {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-danger\" role=\"alert\"><h4>Error!</h4>Current password does not match!</div>
            </div>");
            return(null);
            }
        } else {
            die("Somehow you tried to edit without an account.");
        }
    }

    function signup($fName, $lname, $user, $mail, $confirmE, $pwd, $confirmP) {
        $firstName = htmlspecialchars($fName);
        $lastName = htmlspecialchars($lname);
        $username = htmlspecialchars($user);
        $email = htmlspecialchars($mail);
        $confirmEmail = htmlspecialchars($confirmE);
        $pass = htmlspecialchars($pwd);
        $confirmPass = htmlspecialchars($confirmP);

        // Username Validation
        if(preg_match("/[^a-zA-Z0-9]+/", $user)){
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-warning\" role=\"alert\">Your username must not contain special characters.</div>
            </div>");
            return("Username Error: Invalid");
        }
        else {
            if (mb_strlen($username) > 32) {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-warning\" role=\"alert\">Your username is more than 32 characters.</div>
            </div>");
            return("Username Error: Too many characters");
            } else {
                if (mb_strlen($username) < 4) {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-warning\" role=\"alert\">Your username is less than 4 characters.</div>
            </div>");
            return("Username Error: Not enough characters");
                }
            }
        }

        // Password Validation
        if (mb_strlen($pass) < 8) {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-warning\" role=\"alert\">The password does not meet the requirements.</div>
            </div>");
            return("Password Error: Not enough characters");
        } elseif (mb_strlen($pass) > 64) {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-warning\" role=\"alert\">The password does not meet the requirements. 1</div>
            </div>");
            return("Password Error: Too many characters");
        }

        // Check to see if they are logged in, should not be able to signup while logged in.
        if (isset($_SESSION["id"])) {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-warning\" role=\"alert\"><h4>Error!</h4>You are logged in.</div>
            </div>");
            return(null);
        }

        if ($email === $confirmEmail) {
            if ($pass === $confirmPass) {
                // Check if email already exists
                $statement = $this->UFDatabase->prepare("SELECT User_Email FROM accounts WHERE LOWER(User_Email) = ?");
                $statement->execute([strtolower(htmlspecialchars($email))]);
                $statement->closeCursor();
                if (!empty($statement->rowCount())) {
                    echo("<div class=\"container my-2\">
                        <div class=\"alert alert-warning\" role=\"alert\"><h4>Error!</h4>Email already exists, did you mean to login?<a href=\"login.php\" class=\"btn d-block btn-warning my-2\">Login</a></div>
                    </div>");
                    return("Email Error: Email taken");
                }
                // Check if Username is taken
                $statement = $this->UFDatabase->prepare("SELECT User_Name FROM accounts WHERE LOWER(User_Name) = ?");
                $statement->execute([strtolower(htmlspecialchars($username))]);
                $statement->closeCursor();
                if (!empty($statement->rowCount())) {
                    echo("<div class=\"container my-2\">
                        <div class=\"alert alert-warning\" role=\"alert\"><h4>Sorry!</h4>Username is already taken.</div>
                    </div>");
                    return("Email Error: Email taken");
                }
                $passwordHash = password_hash($pass, PASSWORD_DEFAULT);
                $statement = $this->UFDatabase->prepare("INSERT INTO accounts (User_First, User_Last, User_Name, User_Email, User_Pass) VALUES (?, ?, ?, ?, ?)");
                $statement->execute([$firstName, $lastName, $username, $email, $passwordHash]);
                echo("<div class=\"container my-2\">
                    <div class=\"alert alert-success\" role=\"alert\">Account has been created, you may now login.<br><b>Username:</b> $username<br><b>Email:</b> $email</div>
                </div>");
                return(null);
            }
        } else {
            echo("<div class=\"container my-2\">
                <div class=\"alert alert-danger\" role=\"alert\"><h4>Error!</h4>General error, cannot complete.</div>
            </div>");
            die(0);
        }

    }

    function getInformation($info, $id) {
        $type = htmlspecialchars($info);
        $ID = htmlspecialchars($id);

        if (!isset($_SESSION["id"])) {
            return(null);
        }

        $statement = $this->UFDatabase->prepare("SELECT User_ID, User_First, User_Last, User_Name, User_Email, User_Created FROM accounts WHERE User_ID = ?");
        $statement->execute([$ID]);
        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        if (!empty($statement->rowCount())) {
            switch (strtolower($type)) {
                case strtolower("first"):
                    return($results["User_First"]);
                    break;
                case strtolower("last"):
                    return($results["User_Last"]);
                    break;
                case strtolower("id"):
                    return($results["User_ID"]);
                    break;
                case strtolower("username"):
                    return($results["User_Name"]);
                    break;
                case strtolower("email"):
                    return($results["User_Email"]);
                    break;
                case strtolower("created"):
                    return($results["User_Created"]);
                    break;
                default:
                    break;
            }
        }
    }

    function LogOut() {
        setcookie("Logout", true, time()+10);
        session_destroy();
        header('Location: index.php');
    }
}