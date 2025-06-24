<?php
session_start();
require_once 'Connect.php';

$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$level = 1;
$xp = 0;
$xp_max = 50; // Standardwert, kann nach Belieben angepasst werden

if ($user_email) {
    $db = new Connect();
    $db->connect();
    $stmt = $db->query("SELECT Level, Xp FROM Benutzer WHERE Email = '$user_email'");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $level = (int)$row['Level'];
        $xp = (int)$row['Xp'];
        // Beispiel für dynamisches XP-Maximum:
        $xp_max = 50 + ($level - 1) * 25;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: #f8f8f8;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .profil-info {
            margin: 32px 0 0 32px;
            font-size: 1.2em;
            background: #fff;
            border-radius: 12px;
            padding: 24px 32px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            max-width: 400px;
        }
        .profil-info strong {
            display: inline-block;
            min-width: 78px;
        }
        .xp-bar-container {
            background: #eee;
            border-radius: 6px;
            width: 220px;
            height: 22px;
            display: inline-block;
            vertical-align: middle;
            overflow: hidden;
            margin-left: 10px;
        }
        .xp-bar {
            background: linear-gradient(90deg,#63e6be,#4286f4);
            height: 100%;
            width: <?php echo min(100, ($xp/$xp_max)*100); ?>%;
            transition: width 0.3s;
        }
        .xp-text {
            margin-left: 12px;
            font-size: 1em;
            font-weight: bold;
            color: #4286f4;
        }
    </style>
</head>
<body>
    <div class="profil-info">
        <div><strong>E-Mail:</strong> <?php echo htmlspecialchars($user_email); ?></div>
        <div><strong>Level:</strong> <?php echo $level; ?></div>
        <div style="margin-top: 13px;">
            <strong>XP:</strong>
            <span class="xp-bar-container">
                <span class="xp-bar"></span>
            </span>
            <span class="xp-text"><?php echo $xp . ' / ' . $xp_max; ?></span>
        </div>
    </div>

    <!-- Hier kann dein restlicher Profilinhalt folgen -->

</body>
</html>
