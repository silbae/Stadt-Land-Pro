<?php
require_once 'Connect.php';

// Erstelle ein Connect-Objekt und stelle Verbindung her
$db = new Connect();
$db->connect();

$kategorien = [];
$stm = $db->query("SELECT * FROM Kategorie");
while ($kategorie = $stm->fetch(PDO::FETCH_ASSOC)){
    $kategorien[] = $kategorie;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word']) && isset($_POST['Kategorie'])) {
    $word = $_POST['word'];
    $kategorie = $_POST['Kategorie'];

    // Sicherheit: Prepared Statement verwenden
    $stm = "INSERT INTO Eintrag (Wort, Kategorie) VALUES (:word, :Kategorie)";
    $db->insert($stm, [':word' => $word, ':Kategorie' => $kategorie]);

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
        <?php foreach ($kategorien as $kategorie): ?>
            <option value="<?= htmlspecialchars($kategorie['Name']) ?>"><?= htmlspecialchars($kategorie['Name']) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <button type="submit">Absenden</button>
</form>

</body>
</html>

</body>
</html>
