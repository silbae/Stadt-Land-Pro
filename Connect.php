<?php 

class Connect { 

    private $servername = "localhost"; 
    private $username = "User_Stadt-Land-Pro"; 
    private $password = ""; 
    private $dbname = "Stadt-Land-Pro"; 
    private $conn;

    public function connect() { 
        // Create connection 
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname); 

        // Check connection 

        if ($this->conn->connect_error) { 

            die("Connection failed: " . $this->conn->connect_error); 

        } 
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
        $this->conn->close(); 
    }

} 
?> 