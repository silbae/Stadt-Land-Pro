<?php 
require_once("Connect.php");
session_start();
$conn = new Connect(); 
$conn->connect();

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    // Holt den Benutzer anhand der E-Mail-Adresse
    $user = $conn->select(
        "SELECT * FROM Benutzer WHERE Email = :email",
        array('email' => $email)
    );

    // Prüfe, ob ein Nutzer gefunden wurde und das Passwort stimmt
    if ($user && isset($user['Passwort']) && password_verify($passwort, $user['Passwort'])) {
        $_SESSION['userid'] = $user['Email'];
        $_SESSION['email'] = $user['Email'];
        header("Location: suchleiste.php");
        exit();
    } else {
        $errorMessage = "E-Mail oder Passwort war ungültig<br>";
    }
    $conn->disconnect();
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
<input type="password" size="40" maxlength="250" name="passwort"><br>

<input type="submit" value="Abschicken">
</form> 
</body>
</html>
