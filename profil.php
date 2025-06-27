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
// === PROFILICON SETUP ===
$icon_default = "https://cdn-icons-png.flaticon.com/128/5393/5393061.png"; // Neuer Standard
$icon_level3  = "https://cdn-icons-png.flaticon.com/128/3135/3135768.png"; // Level 3 (alt)
$icon_level5  = "https://cdn-icons-png.flaticon.com/128/4793/4793069.png";
$icon_level8  = "https://i.etsystatic.com/22360457/r/il/640aa2/2247217699/il_570xN.2247217699_hxi5.jpg";
$icon_level10 = "https://cdn-icons-png.flaticon.com/128/2210/2210034.png";

// ProfilIcon aus DB laden
$profil_icon = $icon_default;
if ($user_email) {
    $stmt = $db->queryPrep("SELECT ProfilIcon FROM Benutzer WHERE Email = :email");
    $stmt->execute([':email' => $user_email]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($row['ProfilIcon'])) {
            $profil_icon = $row['ProfilIcon'];
        }
    }
}

// Verfügbare Icons je nach Level
$available_icons = [$icon_default];
if ($level >= 3)  $available_icons[] = $icon_level3;
if ($level >= 5)  $available_icons[] = $icon_level5;
if ($level >= 8)  $available_icons[] = $icon_level8;
if ($level >= 10) $available_icons[] = $icon_level10;

// Wenn ein Icon ausgewählt wurde, speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profil_icon'])) {
    $selected_icon = $_POST['profil_icon'];
    if (in_array($selected_icon, $available_icons)) {
        $stmt = $db->queryPrep("UPDATE Benutzer SET ProfilIcon = :icon WHERE Email = :email");
        $stmt->execute([':icon' => $selected_icon, ':email' => $user_email]);
        $profil_icon = $selected_icon;
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
        .zurueck-btn {
            position: fixed;
            top: 24px;
            right: 32px;
            background: linear-gradient(90deg, #4286f4 40%, #63e6be 100%);
            color: white;
            padding: 10px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1em;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            border: none;
            cursor: pointer;
            letter-spacing: 1px;
            transition: background 0.2s;
            z-index: 999;
            display: inline-block;
        }
        .zurueck-btn:hover {
            background: linear-gradient(90deg, #ff6b6b 10%, #b96bff 100%);
            color: white;
        }
.profil-info {
    margin: 48px auto 0 auto;
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
        .xp-row {
            display: flex;
            align-items: center;
            margin-top: 13px;
        }
        .xp-label {
            font-weight: bold;
            margin-right: 10px;
        }
        .xp-bar-container {
            background: #eee;
            border-radius: 6px;
            width: 220px;
            height: 28px;
            display: flex;
            align-items: center;
            position: relative;
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
            color: #000;
            font-weight: bold;
            font-size: 1.08em;
            letter-spacing: 1px;
            text-shadow: 0 1px 4px #fff6;
            user-select: none;
        }
    </style>
</head>
<body>
    <a href="suchleiste.php" class="zurueck-btn">Zurück</a>
    <div style="text-align:center; margin-top:16px;">
    <img src="<?php echo htmlspecialchars($profil_icon); ?>" alt="Profilicon" style="width:80px; height:80px; border-radius:50%; border:2px solid #4286f4;">
</div>
<?php if ($user_email): ?>
<form method="post" style="margin-top:16px; text-align:center;">
    <?php foreach ($available_icons as $icon): ?>
        <label style="margin:0 10px;">
            <input type="radio" name="profil_icon" value="<?php echo htmlspecialchars($icon); ?>" <?php if ($profil_icon === $icon) echo 'checked'; ?>>
            <img src="<?php echo htmlspecialchars($icon); ?>" style="width:48px; height:48px; border-radius:50%; border:2px solid #ccc;">
        </label>
    <?php endforeach; ?>
    <br>
    <button type="submit" class="zurueck-btn" style="position:static; margin-top:10px;">Icon auswählen</button>
</form>
<?php endif; ?>
    <div class="profil-info">
        <div><strong>E-Mail:</strong> <?php echo htmlspecialchars($user_email); ?></div>
        <div><strong>Level:</strong> <?php echo $level; ?></div>
        <div class="xp-row">
            <span class="xp-label">XP:</span>
            <span class="xp-bar-container">
                <span class="xp-bar"></span>
                <span class="xp-bar-text"><?php echo $xp . ' / ' . $xp_max; ?></span>
            </span>
        </div>
    </div>
</body>
</html>
