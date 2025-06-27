<?php
require_once 'Connect.php';
session_start();
$user_email = $_SESSION['email'] ?? '';
$wort = $_POST['wort'] ?? '';

if (!$user_email || !$wort) {
    http_response_code(400);
    echo json_encode(['success' => false, 'likes' => 0]);
    exit;
}

$db = new Connect();
$db->connect();

// Prüfen, ob Like existiert
$stmt = $db->select("SELECT id FROM Likes WHERE Wort = ? AND nutzer = ?", [$wort, $user_email]);
if ($stmt) {
    // Like entfernen
    $db->queryPrep("DELETE FROM Likes WHERE Wort = ? AND nutzer = ?");
    $db->execute([$wort, $user_email]);
} else {
    // Like speichern
    $db->queryPrep("INSERT INTO Likes (Wort, nutzer) VALUES (?, ?)");
    $db->execute([$wort, $user_email]);
}

// Aktuelle Like-Anzahl zurückgeben
$row = $db->select("SELECT COUNT(*) AS cnt FROM Likes WHERE Wort = ?", [$wort]);
echo json_encode(['success' => true, 'likes' => (int)$row['cnt']]);
