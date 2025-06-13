<?php 
require_once("Connect.php");
session_start();
?>
<!DOCTYPE html> 
<html> 
<head>
  <title>Registrierung</title>    
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
    .register-box {
      background: #f8f9fa;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px #e0e0e0;
      text-align: center;
      min-width: 320px;
    }
    .register-box h2 {
      margin-bottom: 20px;
    }
    .register-input {
      width: 90%;
      padding: 8px;
      margin: 8px 0 16px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1em;
    }
    .register-button {
      background: #28a745;
      color: #fff;
      border: none;
      padding: 10px 30px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1em;
      transition: background 0.2s;
    }
    .register-button:hover {
      background: #19692c;
    }
    .error-message {
      color: #d8000c;
      margin-bottom: 10px;
    }
    .success-message {
      color: #155724;
      background: #d4edda;
      border: 1px solid #c3e6cb;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
    }
    a.login-link {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head> 
<body>
<div class="center-wrapper">
  <div class="register-box">
    <h2>Registrierung</h2>
    <?php
    $showFormular = true;
    if(isset($_GET['register'])) {
        $error = false;
        $email = $_POST['email'];
        $passwort = $_POST['passwort'];
        $passwort2 = $_POST['passwort2'];

        $messages = [];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $messages[] = 'Bitte eine gültige E-Mail-Adresse eingeben';
            $error = true;
        }     
        if(strlen($passwort) == 0) {
            $messages[] = 'Bitte ein Passwort angeben';
            $error = true;
        }
        if($passwort != $passwort2) {
            $messages[] = 'Die Passwörter müssen übereinstimmen';
            $error = true;
        }

        $conn = new Connect(); 
        $conn->connect();

        if(!$error) {
            $user = $conn->select("SELECT * FROM Benutzer WHERE Email = :email", array('email' => $email));
            if($user !== false) {
                $messages[] = 'Diese E-Mail-Adresse ist bereits vergeben';
                $error = true;
            }    
        }

        if(!$error) {    
            $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
            $result = $conn->insert("INSERT INTO Benutzer (Email, Passwort) VALUES (:email, :passwort)", array('email' => $email, 'passwort' => $passwort_hash));
            if($result) {        
                echo '<div class="success-message">Du wurdest erfolgreich registriert. <a class="login-link" href="login.php">Zum Login</a></div>';
                $showFormular = false;
            } else {
                $messages[] = 'Beim Abspeichern ist leider ein Fehler aufgetreten';
            }
        } 

        $conn->disconnect();

        if(!empty($messages)) {
            echo '<div class="error-message">'.implode('<br>', $messages).'</div>';
        }
    }

    if($showFormular) {
    ?>
    <form action="?register=1" method="post">
      <input class="register-input" type="email" maxlength="250" name="email" placeholder="E-Mail" required><br>
      <input class="register-input" type="password" maxlength="250" name="passwort" placeholder="Passwort" required><br>
      <input class="register-input" type="password" maxlength="250" name="passwort2" placeholder="Passwort wiederholen" required><br>
      <input class="register-button" type="submit" value="Abschicken">
    </form>
    <div style="margin-top:16px;">
      <a class="login-link" href="login.php">Bereits registriert? Zum Login</a>
    </div>
    <?php
    }
    ?>
  </div>
</div>
</body>
</html>
