<?php
// Inkludiere die Datenbankverbindung
include ('Connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['word'])) {
    // Eingabewort von POST-Formular erhalten
    $word = $_POST['word'];

    // Sicherheit: Schütze vor SQL-Injektion
    $word = $conn->real_escape_string($word);

    // SQL-Abfrage zum Einfügen des Worts in die Datenbank
    $sql = "INSERT INTO Begriff (word) VALUES ('$word')";

    if ($conn->query($sql) === TRUE) {
        echo "Wort erfolgreich gespeichert!";
    } else {
        echo "Fehler: " . $sql . "<br>" . $conn->error;
    }
}

// Schließe die Verbindung nach der Verarbeitung
$conn->close();
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
