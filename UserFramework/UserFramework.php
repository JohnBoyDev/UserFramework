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
<table>
    <thead>
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

        // $statement = $this->UFDatabase->prepare("SELECT User_ID, User_Name, User_Email, User_Pass FROM accounts");
        // $statement->execute();
        // $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        // foreach ($results as $key => $value) {
        //     array_push($this->tableElements, $value);
        // }
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

        $statement = $this->UFDatabase->prepare("SELECT User_ID, User_Email, User_Pass FROM accounts WHERE User_Email = ?");
        $statement->execute([htmlspecialchars($email)]);
        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        if (!empty($statement->rowCount())) {
            if (password_verify($pass, $results["User_Pass"])) {
                $_SESSION["id"] = $results["User_ID"];
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

    function getInformation($info, $id) {
        $type = htmlspecialchars($info);
        $ID = htmlspecialchars($id);

        if (!isset($_SESSION["id"])) {
            return(null);
        }

        $statement = $this->UFDatabase->prepare("SELECT User_ID, User_Name, User_Email, User_Created FROM accounts WHERE User_ID = ?");
        $statement->execute([$ID]);
        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $statement->closeCursor();

        if (!empty($statement->rowCount())) {
            switch (strtolower($type)) {
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
        session_destroy();
        header('Location: login.php');
    }
}