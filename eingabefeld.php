
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
        <option value="Fluss">Fluss</option>
        <option value="Sportart">Sportart</option>
        <option value="Promi">Promi</option>
        <option value="Name">Name</option>
        <option value="Lebensmittel">Lebensmittel</option>
        <option value="Film">Film</option>
        <option value="Beruf">Beruf</option>
        <!-- Weitere Kategorien können hier ergänzt werden -->
    </select>
    <br><br>
    <button type="submit">Absenden</button>
</form>

</body>
</html>

</body>
</html>
