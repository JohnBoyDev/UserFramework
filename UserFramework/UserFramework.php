<?php

/*

    A Framework based in PHP to make user creation and sessions quick to deploy.
    (c) 2023, John "JohnBoyDev" Channell

*/

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


class UserFramework {
    private $database;
}
