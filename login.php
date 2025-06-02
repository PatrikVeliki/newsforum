<?php

require("./includes/common.inc.php");
require("./includes/config.inc.php");
require("./includes/db.inc.php");


$conn = dbConnect();

ta($_POST);
$msg = "";

if (isset($_POST["btnRegister"])) {
    if ($_POST["P"] !== $_POST["P2"]) {
        $msg = '<p class="error">Die Passöwrter stimmen leider nicht überein!</p>';
    } else {
        if (count($_POST) > 0) {

            $sql = "
        SELECT COUNT(*) AS cnt
        FROM
            tbl_user
        WHERE(
            email='" . $_POST["E"] . "'
        ) 
    ";

            $daten = query($conn, $sql);
            $ds = fetch($conn, $daten);

            if ($ds->cnt == 0) {
                $sql = "
            INSERT INTO
                tbl_user (
                    email,
                    pwd,
                    vorname,
                    nachname,
                    gebDat
            ) VALUES (
                " . value_set($_POST["E"]) . ",
                " . value_set($_POST["P"]) . ",
                " . value_set($_POST["VN"], true) . ",
                " . value_set($_POST["NN"], true) . ",
                " . value_set($_POST["GD"], true) . "
            )
        ";

                $ok = query($conn, $sql);
                if ($ok) {
                    $msg = '<p class="success">Registrierung erfolgreich</p>';
                } else {
                    $msg = '<p class="error">Registrierung fehlgeschlagen</p>';
                }
            } else {
                $msg = '<p class="error">Diese Email existiert bereits';
            }
        }
    }
}

if (isset($_POST["btnLogin"])) {
    if (!empty($_POST["EL"]) && !empty($_POST["PL"])) {
        $sql = "
        SELECT 
            email,
            pwd
        FROM
            tbl_user
        WHERE
            email='" . $_POST["EL"] . "'
    ";

        $daten = query($conn, $sql);
        $user = fetch($conn, $daten);

        if ($user) {
            if ($_POST["PL"] === $user->pwd) {
                $_SESSION["eingeloggt"] = true;
                header("Location: ./forum.php");
            } else {
                $msg = '<p class="error">Leider sind die Login daten nicht korrekt versuche es nocheinmal</p>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/common.css">
</head>

<body>
    <?php echo $msg ?>
    <fieldset>Registrierung
        <form method="post">
            <label>Ihre E-Mail-Adresse:<br>
                <input type="email" name="E" required>
            </label>
            <br>
            <label>Ihr Passwort:<br>
                <input type="password" name="P" required>
            </label>
            <br>
            <label>Passwort Wiederholen:<br>
                <input type="password" name="P2" required>
            </label>
            <br>
            <label>Vorname: <br>
                <input type="text" name="VN">
            </label>
            <br>
            <label>Nachname: <br>
                <input type="text" name="NN">
            </label>
            <br>
            <label>Geburtsdatum: <br>
                <input type="date" name="GD">
            </label>
            <br>
            <input type="submit" value="registrieren" name="btnRegister">
        </form>
    </fieldset>
    <fieldset>Login
        <form method="post">
            <label>Ihre E-Mail-Adresse:<br>
                <input type="email" name="EL" required>
            </label>
            <br>
            <label>Ihr Passwort:<br>
                <input type="password" name="PL" required>
            </label>
            <br>
            <input type="submit" value="einloggen" name="btnLogin">
        </form>
    </fieldset>
</body>

</html>