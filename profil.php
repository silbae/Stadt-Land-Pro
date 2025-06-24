<?php
session_start();
require_once 'Connect.php';

$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$level = 1;
$xp = 0;
$xp_max = 100;

if ($user_email) {
    $db = new Connect();
    $db->connect();
    $stmt = $db->query("SELECT Level, Xp FROM Benutzer WHERE Email = '$user_email'");
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $level = (int)$row['Level'];
        $xp = (int)$row['Xp'];
    }
}

$xp_percent = max(0, min(100, ($xp / $xp_max) * 100));
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
            height: 28px;
            display: flex;
            align-items: center;
            position: relative;
            margin-left: 10px;
            overflow: hidden;
        }
        .xp-bar {
            background: #4286f4;
            height: 100%;
            width: <?php echo $xp_percent; ?>%;
            transition: width 0.3s;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
        }
        .xp-bar-text {
            position: relative;
            z-index: 2;
            width: 100%;
            text-align: center;
            color: #fff;
            font-weight: bold;
            font-size: 1.08em;
            letter-spacing: 1px;
            text-shadow: 0 1px 4px #0002;
            user-select: none;
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
                <span class="xp-bar-text"><?php echo $xp . ' / ' . $xp_max; ?></span>
            </span>
        </div>
    </div>
</body>
</html>
