<?php 
require_once("Connect.php");
session_start();
$conn = new Connect(); 
$conn->connect();

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    // SQL-Statement mit korrekten Spaltennamen und Parametern
    $statement = $conn->queryPrep(
        "SELECT * FROM Benutzer WHERE Email = :email",
        array('email' => $email)
    );
    $user = $statement->fetch();

    // Überprüfung des Passworts – Spaltenname "Passwort"!
    if ($user !== false && password_verify($passwort, $user['Passwort'])) {
        $_SESSION['benutzer'] = $user['Benutzer'];
        die('Login erfolgreich. Weiter zu <a href="geheim.php">internen Bereich</a>');
    } else {
        $errorMessage = "E-Mail oder Passwort war ungültig<br>";
    }
}
?>
<!DOCTYPE html> 
<html> 
<head>
  <title>Login</title>    
</head> 
<body>

<?php 
if(isset($errorMessage)) {
    echo $errorMessage;
}
?>

<form action="" method="post">
<input type="hidden" name="login" value="1">
E-Mail:<br>
<input type="email" size="40" maxlength="250" name="email"><br><br>

Dein Passwort:<br>
<input type="password" size="40"  maxlength="250" name="passwort"><br>

<input type="submit" value="Abschicken">
</form> 
</body>
</html>
