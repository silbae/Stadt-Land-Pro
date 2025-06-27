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

print_r($treffer); // Debug-Ausgabe der Treffer

// Bewertungszahlen holen (sicheres prepare/execute)
$bewertetCounts = [];
if (count($treffer) > 0) {
    $placeholders = implode(',', array_fill(0, count($treffer), '?'));
    $sql = "SELECT wort, bewertet FROM bewertet WHERE wort IN ($placeholders)";
    $stmt = $db->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bewertetCounts[$row['wort']] = $row['bewertet'];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Stadt-Land-Pro Suchleiste</title>
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
        .suchleisten-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 25px;
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
            background: linear-gradient(90deg, #63e6be 40%, #4286f4 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        .such-container button:hover {
            background: linear-gradient(90deg, #4286f4 0%, #b96bff 100%);
        }
        .ergebnisse-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 70px;
            margin-bottom: 120px;
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ergebnisse strong {
            font-size: 1.18em;
        }
        .hinweis {
            color: #888;
            font-style: italic;
            margin-top: 15px;
        }
        .bodenleiste {
            width: 100vw;
            position: fixed;
            left: 0;
            bottom: 0;
            background: linear-gradient(90deg, #fffbe7 0%, #fbe7ff 100%);
            padding: 12px 0;
            box-shadow: 0 -2px 16px rgba(0,0,0,0.08);
            display: flex;
            justify-content: center;
            gap: 30px;
            z-index: 99;
        }
        .bodenleiste img {
            height: 54px;
            border-radius: 9px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.10);
            object-fit: cover;
            transition: transform 0.2s;
        }
        .bodenleiste img:hover {
            transform: scale(1.08) rotate(-2deg);
        }
        .bewerten-btn {
            background: #ffe066;
            color: #333;
            border: 1px solid #ffd700;
            border-radius: 5px;
            padding: 3px 13px;
            margin-left: 15px;
            font-size: 1.06em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .bewerten-btn:hover {
            background: #ffd700;
        }
        .bewertet-count {
            font-weight:bold;
            margin-left: 8px;
            color: #ff8800;
        }
        @media (max-width: 600px) {
            .such-container, .ergebnisse {
                min-width: unset;
                width: 95vw;
            }
            .ergebnisse-wrapper, .suchleisten-wrapper {
                min-width: 100vw;
            }
            .bodenleiste img {
                height: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="fancy-header">Stadt-Land-Pro - Get on the Next Level</div>
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
                        <div class="ergebnis-zeile">
                            <?php echo htmlspecialchars($wort); ?>
                            <button class="bewerten-btn" data-wort="<?php echo htmlspecialchars($wort); ?>" title="Bewerten">👍</button>
                            <span class="bewertet-count" id="bewertet-<?php echo htmlspecialchars($wort); ?>">
                                <?php echo isset($bewertetCounts[$wort]) ? $bewertetCounts[$wort] : 0; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="hinweis">Keine Treffer gefunden.</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="hinweis">Bitte Kategorie und Anfangsbuchstaben auswählen.</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="bodenleiste">
        <a href="https://www.example.com/werbung1" target="_blank"><img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=facearea&w=400&h=400" alt="Werbung 1"></a>
        <a href="https://www.example.com/werbung2" target="_blank"><img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=facearea&w=400&h=400" alt="Werbung 2"></a>
        <a href="https://www.example.com/werbung3" target="_blank"><img src="https://images.unsplash.com/photo-1519985176271-adb1088fa94c?auto=format&fit=facearea&w=400&h=400" alt="Werbung 3"></a>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.bewerten-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const wort = this.dataset.wort;
                fetch('bewertet.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'wort=' + encodeURIComponent(wort)
                })
                .then(response => response.text())
                .then(count => {
                    document.getElementById('bewertet-' + wort).textContent = count;
                });
            });
        });
    });
    </script>
</body>
</html>
