<?php
require_once 'Connect.php';
$db = new Connect();
$db->connect();

if(isset($_POST['wort'])) {
    $wort = $_POST['wort'];
    // Upsert: Bewertung erhöhen oder neuen Datensatz anlegen:
    $sql = "INSERT INTO Bewertungen (wort, bewertet) VALUES (:wort, 1)
            ON DUPLICATE KEY UPDATE bewertet = bewertet + 1";
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute(['wort' => $wort]);

    // Neue Anzahl zurückgeben
    $sql = "SELECT bewertet FROM Bewertungen WHERE wort = :wort";
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute(['wort' => $wort]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $row ? $row['bewertet'] : '1';
}
