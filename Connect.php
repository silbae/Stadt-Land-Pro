<?php 

class Connect { 

    private $servername = "localhost"; 
    private $username = "User_Stadt-Land-Pro"; 
    private $password = ""; 
    private $dbname = "Stadt-Land-Pro"; 
    private $conn;

    public function connect() { 
        // Create connection 
        $this->conn = new PDO('mysql:host='.$this->servername.';dbname='.$this->dbname, $this->username, $this->password); 
    }

    public function query($stm)
    {
        return $this->conn->query($stm);
    }

    public function queryPrep($stm, $params)
    {
        $statement = $this->conn->prepare($stm);
        $statement->execute($params);
        return $statement->fetch();
    }

    public function disconnect() {
        //$this->conn->close(); 
        $pdo = null;
    }

} 
?> 