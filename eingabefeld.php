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
    <title>Wort hinzufügen</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .center-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .eingabe-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #e0e0e0;
            text-align: center;
            min-width: 320px;
        }
        .eingabe-box h2 {
            margin-bottom: 20px;
        }
        .eingabe-input, .eingabe-select {
            width: 90%;
            padding: 8px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        .eingabe-button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.2s;
        }
        .eingabe-button:hover {
            background: #0056b3;
        }
        .success-message {
            color: #155724;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .error-message {
            color: #d8000c;
            margin-bottom: 10px;
        }
        a.link {
            color: #007bff;
            text-decoration: none;
        }
        a.link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="center-wrapper">
    <div class="eingabe-box">
        <h2>Wort hinzufügen</h2>
        <?php echo $meldung; ?>
        <form method="post" action="">
            <input class="eingabe-input" type="text" id="word" name="word" placeholder="Wort eingeben" required>
            <br>
            <select class="eingabe-select" id="Kategorie" name="Kategorie" required>
                <option value="">Kategorie wählen</option>
                <?php foreach ($kategorien as $kategorie): ?>
                    <option value="<?= htmlspecialchars($kategorie['Name']) ?>"><?= htmlspecialchars($kategorie['Name']) ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <button class="eingabe-button" type="submit">Absenden</button>
        </form>
        <div style="margin-top:16px;">
            <a class="link" href="suchleiste.php">Zurück zur Suche</a>
        </div>
    </div>
</div>
</body>
</html>
