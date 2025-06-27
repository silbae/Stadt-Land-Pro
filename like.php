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
$stmt = $db->query("SELECT id FROM Likes WHERE Wort = ? AND nutzer = ?", [$wort, $user_email]);
if ($stmt->fetch()) {
    // Like entfernen
    $db->query("DELETE FROM Likes WHERE Wort = ? AND nutzer = ?", [$wort, $user_email]);
} else {
    // Like speichern
    $db->query("INSERT INTO Likes (Wort, nutzer) VALUES (?, ?)", [$wort, $user_email]);
}

// Aktuelle Like-Anzahl zurückgeben
$res = $db->query("SELECT COUNT(*) AS cnt FROM Likes WHERE Wort = ?", [$wort]);
$row = $res->fetch(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'likes' => (int)$row['cnt']]);
