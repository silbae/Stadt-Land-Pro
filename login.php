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
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #f4f6f8;
    }
    .center-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      background: #f8f9fa;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px #e0e0e0;
      text-align: center;
      min-width: 320px;
    }
    .login-box h2 {
      margin-bottom: 20px;
    }
    .login-input {
      width: 90%;
      padding: 8px;
      margin: 8px 0 16px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1em;
    }
    .login-button {
      background: #007bff;
      color: #fff;
      border: none;
      padding: 10px 30px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1em;
      transition: background 0.2s;
    }
    .login-button:hover {
      background: #0056b3;
    }
    .error-message {
      color: #d8000c;
      margin-bottom: 10px;
    }
  </style>
</head> 
<body>
<div class="center-wrapper">
  <div class="login-box">
    <h2>Login</h2>
    <?php 
    if(isset($errorMessage)) {
        echo '<div class="error-message">'.$errorMessage.'</div>';
    }
    ?>
    <form action="" method="post">
      <input type="hidden" name="login" value="1">
      <input class="login-input" type="email" maxlength="250" name="email" placeholder="E-Mail" required><br>
      <input class="login-input" type="password" maxlength="250" name="passwort" placeholder="Passwort" required><br>
      <input class="login-button" type="submit" value="Abschicken">
    </form>
    <div style="margin-top:16px;">
      <a href="registrieren.php" style="color:#28a745;text-decoration:none;">Noch kein Account? Jetzt registrieren</a>
    </div>
  </div>
</div>
</body>
</html>
