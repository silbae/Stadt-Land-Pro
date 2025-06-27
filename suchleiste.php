<?php //Damian: Hauptseite der Website Stadt Land Pro. Design, Suchabfrage an Datenbank, Anordnung der Elemente, Werbeleiste, Herzen (weiterführend für später Likes), 
//Silas: Verknüpfung Registrieren/Login/Out und Eingabebuttons mit der Seite suchleiste.php, Icon für Benutzer(weiterführend später Account mit Profilbild, Level, etc.)
require_once 'Connect.php';
session_start();
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$db = new Connect();
$db->connect();

// Kategorien aus der Datenbank holen
$kategorien = [];
$sql = "SELECT DISTINCT TRIM(LOWER(kategorie)) AS kategorie FROM Eintrag ORDER BY kategorie ASC";
$result = $db->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $kategorien[] = ucfirst($row['kategorie']);
}

// Eingaben verarbeiten:
$kategorie = isset($_GET['kategorie']) ? $_GET['kategorie'] : '';
$buchstabe = isset($_GET['buchstabe']) ? strtoupper($_GET['buchstabe']) : '';

$treffer = [];
if ($kategorie && $buchstabe) {
    $search = $buchstabe . '%';
    $query = $db->query("SELECT wort FROM Eintrag WHERE kategorie = '$kategorie' AND wort LIKE '$search'");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $wort = $row['wort'];

        $stmtLikes = $db->select("SELECT COUNT(*) AS cnt FROM Likes WHERE Wort = ?", [$wort]);
        $likeCount = $stmtLikes['cnt'] ?? 0;

       // Hat der Nutzer schon geliked?
        $stmtUserLiked = $db->select("SELECT id FROM Likes WHERE Wort = ? AND nutzer = ?", [$wort, $user_email]);
        $userLiked = $stmtUserLiked ? true : false;

        $treffer[] = [
            'wort' => $wort,
            'likes' => $likeCount,
            'userLiked' => $userLiked
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Stadt-Land-Pro Suchleiste</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: #f8f8f8;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .fancy-header {
            margin-top: 32px;
            margin-bottom: 15px;
            font-size: 2.7em;
            font-weight: bold;
            letter-spacing: 2px;
            text-align: center;
            background: linear-gradient(90deg, #ff6b6b, #f8e71c, #63e6be, #4286f4, #b96bff, #ff6b6b);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientMove 7s ease-in-out infinite;
            user-select: none;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .suchleisten-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 25px;
            position: relative;
        }
        .such-container {
            background: #fff;
            padding: 28px 36px 28px 36px;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.09);
            text-align: center;
            min-width: 340px;
        }
        .such-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        .such-container select,
        .such-container input[type="text"] {
            font-size: 1.15em;
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #bbb;
            width: 220px;
        }
        .such-container button {
            font-size: 1.08em;
            padding: 7px 24px;
            border-radius: 6px;
            background: linear-gradient(90deg, #63e6be 40%, #4286f4 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        .such-container button:hover {
            background: linear-gradient(90deg, #4286f4 0%, #b96bff 100%);
        }
        .ergebnisse-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 70px;
    margin-bottom: 120px;
}

.ergebnisse {
    background: #fff;
    min-width: 600px;  
    max-width: 900px;  
    width: 100%;
    padding: 26px 48px; 
    border-radius: 16px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.09);
    text-align: center;
}
     .ergebnis-zeile {
    font-size: 1.18em;
    color: #1a1a1a;
    margin: 8px 0 8px 0;
    border-bottom: 1px solid #ececec;
    padding-bottom: 4px;
    display: flex;
    align-items: center;
    justify-content: center; 
}
        .ergebnisse strong {
            font-size: 1.18em;
        }
        .hinweis {
            color: #888;
            font-style: italic;
            margin-top: 15px;
        }
       .bodenleiste {
    width: 100vw;
    position: fixed;
    left: 0;
    bottom: 0;
    background: linear-gradient(90deg, #fffbe7 0%, #fbe7ff 100%);
    padding: 12px 0;
    box-shadow: 0 -2px 16px rgba(0,0,0,0.08);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 18px; /* enger, damit mehr Spots passen */
    z-index: 99;
    flex-wrap: wrap;
}

.bodenleiste img {
    height: 54px;
    border-radius: 9px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.10);
    object-fit: cover;
    transition: transform 0.2s;
}

.bodenleiste img:hover {
    transform: scale(1.08) rotate(-2deg);
}

.anzeigenfeld {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 54px;
    min-width: 210px;
    background: #fffbe7;
    border-radius: 9px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.10);
    font-size: 1.19em;
    color: #b96bff;
    font-weight: bold;
    padding: 0 18px;
}

/* Mobilanpassung */
@media (max-width: 600px) {
    .bodenleiste {
        gap: 6px;
        flex-wrap: wrap;
    }
    .bodenleiste img,
    .anzeigenfeld {
        height: 36px;
        font-size: 1em;
        min-width: 120px;
        padding: 0 6px;
    }
}
        @media (max-width: 600px) {
            .such-container, .ergebnisse {
                min-width: unset;
                width: 95vw;
            }
            .ergebnisse-wrapper, .suchleisten-wrapper {
                min-width: 100vw;
            }
            .bodenleiste img {
                height: 36px;
            }
        }
        .logout-btn {
            position: absolute;
            top: 24px;
            right: 32px;
            background: linear-gradient(90deg, #4286f4 40%, #63e6be 100%);
            color: white;
            padding: 8px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.12em;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            transition: background 0.25s;
            border: none;
            cursor: pointer;
            letter-spacing: 1px;
        }
        .logout-btn:hover {
            background: linear-gradient(90deg, #ff6b6b 10%, #b96bff 100%);
        }
        .user-info {
            position: absolute;
            top: 22px;
            left: 32px;
            display: flex;
            align-items: center;
            background: linear-gradient(90deg, #4286f4 40%, #63e6be 100%);
            color: white;
            padding: 7px 18px 7px 10px;
            border-radius: 7px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            font-weight: bold;
            font-size: 1.08em;
            gap: 12px;
            z-index: 100;
        }
        .user-info .icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 6px;
        }
        .user-info .icon img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
        }
        .eingabe-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 20px;
    height: 56px;
    background: linear-gradient(90deg, #ff6b6b 0%, #4286f4 100%);
    color: white;
    padding: 0 26px;
    border-radius: 10px;
    font-size: 1.11em;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    border: none;
    cursor: pointer;
    transition: background 0.25s;
    min-width: 120px;
}
.suchleisten-wrapper {
    width: 100%;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 25px;
}

.such-container {
    background: #fff;
    padding: 28px 36px;
    border-radius: 16px;
    box-shadow: 0 4px 32px rgba(0,0,0,0.09);
    text-align: center;
    min-width: 340px;
    z-index: 1;
}

.eingabe-btn {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    height: 56px;
    background: linear-gradient(90deg, #ff6b6b 0%, #4286f4 100%);
    color: white;
    padding: 0 26px;
    border-radius: 10px;
    font-size: 1.11em;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    border: none;
    cursor: pointer;
    transition: background 0.25s;
    min-width: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}
.eingabe-btn:hover {
    background: linear-gradient(90deg, #63e6be 0%, #b96bff 100%);
}
@media (max-width: 700px) {
    .suchleisten-wrapper {
        flex-direction: column;
        align-items: center;
    }
    .eingabe-btn {
        position: static;
        margin-top: 18px;
        width: 95vw;
        height: 48px;
        transform: none;
    }
    .such-container {
        width: 95vw;
        min-width: unset;
    }
}
     .floating-eingabe-btn {
    position: fixed;
    right: 32px;      /* Abstand zum rechten Rand */
    bottom: 90px;     /* Abstand zur Bannerleiste (passe ggf. an) */
    z-index: 999;
    height: 56px;
    background: linear-gradient(90deg, #ff6b6b 0%, #4286f4 100%);
    color: white;
    padding: 0 26px;
    border-radius: 10px;
    font-size: 1.11em;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    border: none;
    cursor: pointer;
    transition: background 0.25s;
    min-width: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.floating-eingabe-btn:hover {
    background: linear-gradient(90deg, #63e6be 0%, #b96bff 100%);
}
@media (max-width: 700px) {
    .floating-eingabe-btn {
        right: 12px;
        bottom: 82px;
        width: 90vw;
        min-width: unset;
        height: 48px;
        font-size: 1em;
    }
}
.ergebnisse {
    min-width: 350px !important;
    max-width: 400px;
    padding: 30px 10px !important;
    border-radius: 14px;
    font-size: 1.1em;
}

.ergebnis-zeile {
    font-size: 1em;
    color: #1a1a1a;
    margin: 8px 0;
    border-bottom: 1px solid #ececec;
    padding: 10px 0;
    min-width: 0;
    width: 100%;
    max-width: 350px;
    background: #f8f8f8;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    word-break: break-word;
}
.ergebnis-zeile {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 8px 0;
}

.herz-btn {
    background: none;
    border: none;
    font-size: 1.4em;
    color: #bbb;
    cursor: pointer;
    transition: color 0.2s, transform 0.2s;
    padding: 0 6px;
}
.herz-btn:hover, .herz-btn.active {
    color: #ff6b6b;
    transform: scale(1.2);
}
        .user-info { cursor: pointer; }
        .profil-btn {
    display: inline-block;
    margin-top: 10px;
    margin-left: 32px;
    background: linear-gradient(90deg, #4286f4 40%, #63e6be 100%);
    color: white;
    padding: 8px 24px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.12em;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    transition: background 0.25s;
    border: none;
    cursor: pointer;
    letter-spacing: 1px;
}
.profil-btn:hover {
    background: linear-gradient(90deg, #ff6b6b 10%, #b96bff 100%);
    color: white;
}
</style>
</head>
<body>
<a href="profil.php" class="user-info" style="text-decoration: none;">
    <span class="icon">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Benutzericon">
    </span>
    <?php echo htmlspecialchars($user_email); ?>
</a>
<a href="logout.php" class="logout-btn">Logout</a>
<div class="fancy-header">Stadt-Land-Pro - Get on the Next Level</div>
<div class="suchleisten-wrapper">
    <div class="such-container">
        <form method="get">
            <label>
                Kategorie:<br>
                <select name="kategorie">
                    <option value="">-- auswählen --</option>
                    <?php foreach ($kategorien as $kat): ?>
                        <option value="<?php echo htmlspecialchars($kat); ?>" <?php if ($kat == $kategorie) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($kat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                Anfangsbuchstabe:<br>
                <input type="text" name="buchstabe" maxlength="1" style="text-transform: uppercase;" value="<?php echo htmlspecialchars($buchstabe); ?>">
            </label>
            <button type="submit">Suchen</button>
        </form>
            </div>
<a href="eingabefeld.php" class="floating-eingabe-btn">Zur Eingabe</a>
</div>
<div class="ergebnisse-wrapper">
    <div class="ergebnisse">
        <?php if ($kategorie && $buchstabe): ?>
            <?php if (count($treffer) > 0): ?>
                <strong>Gefundene Wörter:</strong><br>
              <?php foreach ($treffer as $item): ?>
<div class="ergebnis-zeile">
    <?php echo htmlspecialchars($item['wort']); ?>
    <button class="herz-btn<?php if ($item['userLiked']) echo ' active'; ?>"
            type="button"
            data-wort="<?php echo htmlspecialchars($item['wort']); ?>"
            title="Gefällt mir">&#10084;</button>
    <span class="like-count"><?php echo $item['likes']; ?></span>
</div>
<?php endforeach; ?>
            <?php else: ?>
                <div class="hinweis">Keine Treffer gefunden.</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="hinweis">Bitte Kategorie und Anfangsbuchstaben auswählen.</div>
        <?php endif; ?>
    </div>
</div>
<div class="bodenleiste">
    <a href="https://www.spotlinks.de/werbung_left1" target="_blank"><img src="https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=facearea&w=400&h=400" alt="Werbung L1"></a>
    <a href="https://www.spotlinks.de/werbung_left2" target="_blank"><img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=facearea&w=400&h=400" alt="Werbung L2"></a>
    <div class="anzeigenfeld">Hier könnte Ihre Werbung stehen</div>
    <a href="https://www.example.com/werbung1" target="_blank"><img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=facearea&w=400&h=400" alt="Werbung 1"></a>
    <a href="https://www.example.com/werbung2" target="_blank"><img src="https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=facearea&w=400&h=400" alt="Werbung 2"></a>
    <a href="https://www.example.com/werbung3" target="_blank"><img src="https://images.unsplash.com/photo-1519985176271-adb1088fa94c?auto=format&fit=facearea&w=400&h=400" alt="Werbung 3"></a>
    <div class="anzeigenfeld">Hier könnte Ihre Werbung stehen</div>
    <a href="https://www.spotlinks.de/werbung_right1" target="_blank"><img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=facearea&w=400&h=400" alt="Werbung R1"></a>
    <a href="https://www.spotlinks.de/werbung_right2" target="_blank"><img src="https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=facearea&w=400&h=400" alt="Werbung R2"></a>
</div>
<script>
document.querySelectorAll('.herz-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const wort = this.getAttribute('data-wort');
        console.log('Wort:', wort);
        fetch('like.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'wort=' + encodeURIComponent(wort)
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            if(data.success) {
                this.classList.toggle('active');
                this.nextElementSibling.textContent = data.likes;
            }
        });
    });
});
</script>
</body>
</html>
