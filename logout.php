<?php //Silas
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <style>
        body {
            background: #f8f8f8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .back-btn {
            margin-top: 32px;
            padding: 12px 32px;
            font-size: 1.15em;
            border-radius: 8px;
            background: linear-gradient(90deg, #63e6be 40%, #4286f4 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: linear-gradient(90deg, #4286f4 0%, #b96bff 100%);
        }
    </style>
</head>
<body>
    <h2>Du wurdest erfolgreich ausgeloggt.</h2>
    <a href="index.php" class="back-btn">Zurück zur Startseite</a>
</body>
</html>
