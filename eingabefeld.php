<?php
include 'Connect.php';

// Erstelle ein Connect-Objekt und stelle Verbindung her
$db = new Connect();
$db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['word'])) {
    $word = $_POST['word'];

    // Sicherheit: Schütze vor SQL-Injektion mit Prepared Statements
    $stm = "INSERT INTO Begriff (word) VALUES (:word)";
    $db->queryPrep($stm, [':word' => $word]);

    echo "Wort erfolgreich gespeichert!";
}

// Verbindung muss nicht explizit geschlossen werden, PDO macht das automatisch beim Script-Ende
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wörter speichern</title>
</head>
<body>

<h1>Wort hinzufügen</h1>

<form method="post" action="index.php">
    <label for="word">Wort eingeben:</label>
    <input type="text" id="Wort hinzufügen" name="Wort hinzufügen" required>
    <button type="submit">Absenden</button>
</form>

</body>
</html>
