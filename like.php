<?php //Damian: anderer Ansatz wie bewertet.php, auch unvollständig
require_once 'Connect.php';
session_start();

$db = new Connect();
$db->connect();

$wort = $_POST['wort'] ?? '';
$user = $_SESSION['email'] ?? '';

if ($wort) {
    $stmt = $db->prepare("INSERT INTO Likes (wort, nutzer) VALUES (?, ?)");
    $stmt->execute([$wort, $user]);
}

// Like-Zahl zurückgeben
function getLikes($db, $wort) {
    $sql = "SELECT COUNT(*) AS likes FROM Likes WHERE wort = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$wort]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['likes'] : 0;
}
echo getLikes($db, $wort);
