<?php
require_once 'Connect.php';

// Verbindung aufbauen
$db = new Connect();
$db->connect();

// Kategorien aus der Datenbank holen (eindeutig und normalisiert)
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
            background: #f8f8f8;
            font-family: Arial, sans-serif;
        }
        .suchleisten-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 60px; /* Suchleiste nach oben versetzt */
            position: relative;
        }
        .such-container {
            background: #fff;
            padding: 28px 36px 28px 36px;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.09);
            text-align: center;
            min-width: 340px;
        }
        .such-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        .such-container select,
        .such-container input[type="text"] {
            font-size: 1.15em;
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #bbb;
            width: 220px;
        }
        .such-container button {
            font-size: 1.08em;
            padding: 7px 24px;
            border-radius: 6px;
            background: #4286f4;
            color: white;
            border: none;
            cursor: pointer;
        }
        .ergebnisse-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            position: absolute;
            left: 0;
            top: 45vh; /* Ergebnisse zentral weiter unten im Bild */
            transform: translateY(-50%);
            z-index: 2;
        }
        .ergebnisse {
            background: #fff;
            min-width: 340px;
            padding: 26px 32px;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.09);
            text-align: center;
        }
        .ergebnis-zeile {
            font-size: 1.18em;
            color: #1a1a1a;
            margin: 8px 0 8px 0;
            border-bottom: 1px solid #ececec;
            padding-bottom: 4px;
        }
        .ergebnisse strong {
            font-size: 1.18em;
        }
        .hinweis {
            color: #888;
            font-style: italic;
            margin-top: 15px;
        }
        @media (max-width: 600px) {
            .such-container, .ergebnisse {
                min-width: unset;
                width: 95vw;
            }
            .ergebnisse-wrapper, .suchleisten-wrapper {
                min-width: 100vw;
            }
        }
    </style>
</head>
<body>
    <div class="suchleisten-wrapper">
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
        </div>
    </div>
    <div class="ergebnisse-wrapper">
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
