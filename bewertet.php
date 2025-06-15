<?php //Damian, aber unvollständig ohne Funktion... Idee noch nicht umgesetzt
require_once 'Connect.php';
session_start();

// Benutzer muss eingeloggt sein, E-Mail aus Session holen
if(!isset($_SESSION['email'])) {
    http_response_code(403);
    echo "Nicht eingeloggt";
    exit;
}

$email = $_SESSION['email'];
$wort = isset($_POST['wort']) ? $_POST['wort'] : '';

if(!$wort) {
    http_response_code(400);
    echo "Kein Wort angegeben";
    exit;
}

$db = new Connect();
$db->connect();

// Prüfen, ob schon ein Eintrag für dieses Wort & Email existiert
$sql = "SELECT COUNT(*) FROM bewertet WHERE Email = ? AND Wort = ?";
$stmt = $db->pdo->prepare($sql);
$stmt->execute([$email, $wort]);
$count = $stmt->fetchColumn();

if ($count == 0) {
    // Wenn noch nicht bewertet, Eintrag hinzufügen
    $sql = "INSERT INTO bewertet (Email, Wort) VALUES (?, ?)";
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute([$email, $wort]);
}

// Anzahl der Bewertungen für das Wort zählen und zurückgeben
$sql = "SELECT COUNT(*) FROM bewertet WHERE Wort = ?";
$stmt = $db->pdo->prepare($sql);
$stmt->execute([$wort]);
$anzahl = $stmt->fetchColumn();

echo $anzahl;
?>

