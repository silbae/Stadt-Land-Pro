<?php
require_once 'Connect.php';

// Datenbankverbindung herstellen
$db = new Connect();
$db->connect();

// Kategorien aus der Datenbank holen:
$kategorien = [];
$sql = "SELECT DISTINCT kategorie FROM Eintrag ORDER BY kategorie ASC";
$result = $db->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $kategorien[] = $row['kategorie'];
}

// Eingaben verarbeiten:
$kategorie = isset($_GET['kategorie']) ? $_GET['kategorie'] : '';
$buchstabe = isset($_GET['buchstabe']) ? strtoupper($_GET['buchstabe']) : '';

$treffer = [];
if ($kategorie && $buchstabe) {
    $search = $buchstabe . '%';
    // Mehrere Ergebnisse holen, daher query statt queryPrep:
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
</head>
<body>
    <form method="get">
        <label>
            Kategorie:
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
            Anfangsbuchstabe:
            <input type="text" name="buchstabe" maxlength="1" value="<?php echo htmlspecialchars($buchstabe); ?>">
        </label>
        <button type="submit">Suchen</button>
    </form>

    <div style="margin-top:20px; padding:10px; border:1px solid #ccc; min-height:100px;">
        <?php if ($kategorie && $buchstabe): ?>
            <?php if (count($treffer) > 0): ?>
                <strong>Gefundene Wörter:</strong><br>
                <?php echo implode(", ", $treffer); ?>
            <?php else: ?>
                <em>Keine Treffer gefunden.</em>
            <?php endif; ?>
        <?php else: ?>
            <em>Bitte Kategorie und Anfangsbuchstaben auswählen.</em>
        <?php endif; ?>
    </div>
</body>
</html>
