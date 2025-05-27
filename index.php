<?php 

$servername = "localhost"; 
$username = "User_Stadt-Land-Pro"; 
$password = ""; 
$dbname = "Stadt-Land-Pro"; 


// Create connection 
$conn = new mysqli($servername, $username, $password, $dbname); 

// Check connection 

if ($conn->connect_error) { 

    die("Connection failed: " . $conn->connect_error); 

} 

$sql = "SELECT * FROM Eintrag"; 
$result = $conn->query($sql); 

if ($result->num_rows > 0) { 
    // output data of each row 
    while($row = $result->fetch_assoc()) { 
        echo "Wort: ".$row["Wort"]." - Kategorie: ".$row["Kategorie"]."<br>"; 
    } 

} else { 

    echo "0 results"; 

} 

$conn->close(); 

?> 

