<?php //Silas (ungenutz, da nach dem Login sofort eine Weiterleitung zu suchleiste.php erfolgt)
session_start();
if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">einloggen</a>');
}
 
//Abfrage der Nutzer ID vom Login
$userid = $_SESSION['userid'];
 
echo "Hallo User: ".$userid;
echo '<br><a href="logout.php">Logout</a>';
?>
