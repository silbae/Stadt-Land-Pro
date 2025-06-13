<?php //Silas

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

    public function queryPrep($stm)
    {
        $statement = $this->conn->prepare($stm);
        return $statement;
    }

    public function insert($stm, $params)
    {
        $stmt = $this->queryPrep($stm);
        return $stmt->execute($params);
    }

    public function select($stm, $params)
    {
        $stmt = $this->queryPrep($stm);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function disconnect() {
        //$this->conn->close(); 
        $pdo = null;
    }

} 
?> 
