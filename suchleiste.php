<?php
require_once 'Connect.php';

// Verbindung aufbauen
$db = new Connect();
$db->connect();

// Kategorien aus der Datenbank holen (nur eindeutige, kleingeschriebene, getrimmte Werte)
$kategorien = [];
$sql = "SELECT DISTINCT TRIM(LOWER(kategorie)) AS kategorie FROM Eintrag ORDER BY kategorie ASC";
$result = $db->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $kategorien[] = ucfirst($row['kategorie']); // Für schöne Anzeige
}

// Eingaben verarbeiten:
$kategorie = isset($_GET['kategorie']) ? $_GET['kategorie'] : '';
$buchstabe = isset($_GET['buchstabe']) ? strtoupper($_GET['buchstabe']) : '';

$treffer = [];
if ($kategorie && $buchstabe) {
    // Prepared Statement für Sicherheit (z.B. gegen SQL-Injection)
    $search = $buchstabe . '%';
    $query = $db->query("SELECT wort FROM Eintrag WHERE kategorie = '$kategorie' AND wort LIKE '$search'");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $treffer[] = $row['wort'];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Suchleiste</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: #f8f8f8;
        }
        .such-container {
            background: #fff;
            padding: 32px 40px 32px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.09);
            margin-top: 40px;
            text-align: center;
        }
        .such-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
        }
        .such-container select,
        .such-container input[type="text"] {
            font-size: 1.3em;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #bbb;
            width: 230px;
        }
        .such-container button {
            font-size: 1.1em;
            padding: 7px 22px;
            border-radius: 6px;
            background: #4286f4;
            color: white;
            border: none;
            cursor: pointer;
        }
        .ergebnisse {
            margin-top: 32px;
            padding: 16px 0;
            border-top: 1px solid #eee;
            min-height: 60px;
        }
        .ergebnis-zeile {
            font-size: 1.25em;
            color: #1a1a1a;
            margin: 7px 0;
        }
        .hinweis {
            color: #888;
            font-style: italic;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="such-container">
    <form method="get">
        <label>
            Kategorie:<br>
            <select name="kategorie">
                <option value="">-- auswählen --</option>
                <?php foreach ($kategorien as $kat): ?>
                    <option value="<?php echo htmlspecialchars($kat); ?>" <?php if ($kat == $kategorie) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($kat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Anfangsbuchstabe:<br>
            <input type="text" name="buchstabe" maxlength="1" style="text-transform: uppercase;" value="<?php echo htmlspecialchars($buchstabe); ?>">
        </label>
        <button type="submit">Suchen</button>
    </form>

    <div class="ergebnisse">
        <?php if ($kategorie && $buchstabe): ?>
            <?php if (count($treffer) > 0): ?>
                <strong>Gefundene Wörter:</strong><br>
                <?php foreach ($treffer as $wort): ?>
                    <div class="ergebnis-zeile"><?php echo htmlspecialchars($wort); ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="hinweis">Keine Treffer gefunden.</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="hinweis">Bitte Kategorie und Anfangsbuchstaben auswählen.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
