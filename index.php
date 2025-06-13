<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    // Auswahlseite: Login oder Registrierung
    echo '
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;">
        <div style="background:#f8f9fa;padding:30px;border-radius:8px;box-shadow:0 2px 8px #e0e0e0;text-align:center;">
            <h2>Willkommen zu Stadt Land Pro</h2>
            <p>Bitte wählen Sie eine Option:</p>
            <a href="login.php" style="display:inline-block;margin:10px;padding:10px 30px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;">Login</a>
            <a href="registrieren.php" style="display:inline-block;margin:10px;padding:10px 30px;background:#28a745;color:#fff;text-decoration:none;border-radius:5px;">Registrieren</a>
        </div>
    </div>
    ';
    exit();
}
/*
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
*/
?>
