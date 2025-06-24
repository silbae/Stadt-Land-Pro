<?php
session_start();
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
</head>
<body>
    <div style="margin: 24px 0 0 32px; font-size: 1.2em; font-weight: bold;">
        <?php echo htmlspecialchars($user_email); ?>
    </div>
    <!-- Rest deiner Profilseite -->
</body>
</html>
