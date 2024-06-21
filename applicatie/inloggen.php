<?php
require_once 'functies/db_connectie.php';
require_once 'functies/header.php';
require_once 'functies/footer.php';
$db = maakVerbinding();

$allebalienummers = "SELECT DISTINCT balienummer FROM balie ORDER BY 1 ASC";
$dropPrep = $db->prepare($allebalienummers);
$dropPrep->execute();
$keuze = "";
$fouten = [];
$melding = "";

foreach ($dropPrep as $option) {
    $keuze .= '<option value="' . $option['balienummer'] . '">'  . $option['balienummer'] . '</option>';
}

if (isset($_POST['inloggen'])) {
    if (!empty($_POST['balienummer']) && !empty($_POST['passagiernummer'])) {
        $fouten[] = "Je kan niet als balie en passagier tegelijk inloggen";
    } elseif (!empty($_POST['balienummer'])) {
        $balienummer = $_POST['balienummer'];
        $balieWachtwoord  = $_POST['wachtwoord'];

        $checkQuery = "SELECT balienummer, wachtwoord FROM balie WHERE balienummer = :balienummer";
        $gegevensChecken = $db->prepare($checkQuery);
        $gegevensChecken->execute(['balienummer' => $balienummer]);

        if ($rij = $gegevensChecken->fetch()) {
            $wachtwoordHash = $rij['wachtwoord'];
            if (password_verify($balieWachtwoord, $wachtwoordHash)) {
                session_start();
                $_SESSION['balienummer'] = $balienummer;
                header('location: Medewerker/Balie.php');
            } else {
                $fouten[] = "Combinatie van inloggegevens zijn incorrect";
            }
        } else {
            $fouten[] = "balienummer is niet gevonden";
        }
    } else if (!empty($_POST['passagiernummer'])) {
        $passagiernummer = $_POST['passagiernummer'];
        $passagierWachtwoord  = $_POST['wachtwoord'];

        $checkQuery = "SELECT passagiernummer, wachtwoord FROM passagier WHERE passagiernummer = :passagiernummer";
        $gegevensChecken = $db->prepare($checkQuery);
        $gegevensChecken->execute(['passagiernummer' => $passagiernummer]);

        if ($rij = $gegevensChecken->fetch()) {
            $wachtwoordHash = $rij['wachtwoord'];
        }

        if (empty($fout)) {
            if (password_verify($passagierWachtwoord, $wachtwoordHash)) {
                session_start();
                $_SESSION['passagiernummer'] = $passagiernummer;
                header('location: index.php');
            } else {
                $fouten[] = "Combinatie van inloggegevens zijn incorrect";
            }
            if (!is_numeric($passagiernummer)) {
                $fouten[] = "passagiernummer must be a number";
            }
        } else {
            $fouten[] = "passagiernummer is niet ingevuld";
        }
    } elseif (empty($_POST['passagiernummer']) && empty($_POST['balienummer'])) {
        $melding .= "Vul gegevens in";
    }
    foreach ($fouten as $fout) {
        $melding .= "<li>" . $fout . "</li>";
    }
} 

?>


<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/normalize.css">
    <link rel="stylesheet" href="../CSS/Style.css">
    <link rel="stylesheet" href="../CSS/Forum.css">
    <title>Inloggen</title>
</head>

<body>
    <?= HEADER?>

    <main>

        <section>
            <h2>inlogveld:</h2>
            <form action="" method="post">
                <label for="passagiernummer">Passagier</label>
                <input type="number" id="passagiernummer" name="passagiernummer" placeholder="Nummer">
                <label for="balienummer">Balie</label>
                <select id="balienummer" name="balienummer">
                    <option value="" disabled selected>Nummer</option>
                    <?= $keuze ?>
                </select>
                <label for="wachtwoord">Wachtwoord:</label>
                <p><?= $melding ?></p>
                <input type="password" id="wachtwoord" name="wachtwoord">
                <input type="submit" value="inloggen" id="inloggen" name="inloggen">
            </form>
        </section>

    </main>

    <?= FOOTER ?>
    
</body>

</html>