<?php
include 'Connect.php';

// Erstelle ein Connect-Objekt und stelle Verbindung her
$db = new Connect();
$db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['word']) && isset($_POST['Kategorie'])) {
    $word = $_POST['word'];
    $kategorie = $_POST['Kategorie'];

    // Sicherheit: Prepared Statement verwenden
    $stm = "INSERT INTO Eintrag (word, Kategorie) VALUES (:word, :Kategorie)";
    $db->queryPrep($stm, [':word' => $word, ':Kategorie' => $Kategorie]);

    echo "Wort erfolgreich gespeichert!";
}
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

<form method="post" action="">
    <label for="word">Wort eingeben:</label>
    <input type="text" id="word" name="word" required>
    <br><br>
    <label for="Kategorie">Kategorie wählen:</label>
    <select id="Kategorie" name="Kategorie" required>
        <option value="">Bitte wählen</option>
        <option value="Stadt">Stadt</option>
        <option value="Tier">Tier</option>
        <option value="Land">Land</option>
        <!-- Weitere Kategorien können hier ergänzt werden -->
    </select>
    <br><br>
    <button type="submit">Absenden</button>
</form>

</body>
</html>

</body>
</html>
