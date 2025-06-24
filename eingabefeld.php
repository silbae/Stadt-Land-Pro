<?php //Kilian: Eingabefeld zum Hinzufügen von Wörtern auf der Website in die Datenbank
session_start();
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

    // Prüfen, ob das Wort schon existiert
    $checkStm = $db->insert("SELECT COUNT(*) FROM Eintrag WHERE Wort = :word AND Kategorie = :Kategorie", [
        ':word' => $word,
        ':Kategorie' => $kategorie
    ]);
    if ($checkStm) {
        echo '<div class="error-message">Dieses Wort existiert in dieser Kategorie bereits!</div>';
    } else {
        $stm = "INSERT INTO Eintrag (Wort, Kategorie) VALUES (:word, :Kategorie)";
        $db->insert($stm, [':word' => $word, ':Kategorie' => $kategorie]);

        // XP und Level bearbeiten
      if (isset($_SESSION['email'])) {
    $user_email = $_SESSION['email'];
    // 1. XP erhöhen
    $db->insert("UPDATE Benutzer SET Xp = Xp + 12 WHERE Email = :email", [':email' => $user_email]);

    // 2. Aktuellen XP- und Level-Stand abfragen
    $stmt = $db->insert("SELECT Xp, Level FROM Benutzer WHERE Email = :email", [':email' => $user_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Level-Up prüfen und ggf. durchführen
    while ($user['Xp'] >= 100) {
        $user['Xp'] -= 100;
        $user['Level'] += 1;
        $db->insert("UPDATE Benutzer SET Xp = :xp, Level = :level WHERE Email = :email", [
            ':xp' => $user['Xp'],
            ':level' => $user['Level'],
            ':email' => $user_email
        ]);
    }
}

        echo '<div class="success-message">Wort erfolgreich gespeichert!</div>';
    }
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
            min-height: 100vh;
            margin: 0;
            background: #f8f8f8;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .fancy-header {
            margin-top: 32px;
            margin-bottom: 15px;
            font-size: 2.7em;
            font-weight: bold;
            letter-spacing: 2px;
            text-align: center;
            background: linear-gradient(90deg, #ff6b6b, #f8e71c, #63e6be, #4286f4, #b96bff, #ff6b6b);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientMove 7s ease-in-out infinite;
            user-select: none;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .center-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 40px;
        }
        .eingabe-box {
            background: #fff;
            padding: 28px 36px;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.09);
            text-align: center;
            min-width: 340px;
        }
        .eingabe-box h2 {
            margin-bottom: 20px;
            font-size: 1.7em;
            font-weight: bold;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #ff6b6b, #63e6be, #4286f4, #b96bff, #ff6b6b);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientMove 7s ease-in-out infinite;
            user-select: none;
        }
        .eingabe-input, .eingabe-select {
            font-size: 1.15em;
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #bbb;
            width: 220px;
            margin-bottom: 16px;
        }
        .eingabe-button {
            font-size: 1.08em;
            padding: 7px 24px;
            border-radius: 6px;
            background: linear-gradient(90deg, #63e6be 40%, #4286f4 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
            margin-top: 8px;
        }
        .eingabe-button:hover {
            background: linear-gradient(90deg, #4286f4 0%, #b96bff 100%);
        }
        .success-message {
            color: #155724;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 1.08em;
        }
        .error-message {
            color: #d8000c;
            margin-bottom: 10px;
        }
        a.link {
            color: #4286f4;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.2s;
        }
        a.link:hover {
            color: #ff6b6b;
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .eingabe-box {
                min-width: unset;
                width: 95vw;
            }
            .center-wrapper {
                min-width: 100vw;
            }
        }
    </style>
</head>
<body>
<div class="fancy-header">Stadt-Land-Pro – Wort hinzufügen</div>
<div class="center-wrapper">
    <div class="eingabe-box">
            <div class="success-message">Wort erfolgreich gespeichert!</div>
        <h2>Wort hinzufügen</h2>
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
