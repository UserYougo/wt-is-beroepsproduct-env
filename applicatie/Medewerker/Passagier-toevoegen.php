<?php
session_start();
require_once '../functies/db_connectie.php';
require_once '../functies/medewerkerHeader.php';
require_once '../functies/footer.php';
$db = maakVerbinding();

$fouten = [];
$melding = "";
$passagiernummer = "";
$naam = "";
$vluchtnummer = "";
$geslacht = "";
$balienummer = "";
$stoel = "";
$wachtwoord = "";

if(!isset($_SESSION['balienummer'])){
    header('location: index.php');
    exit;
}

if ($_SESSION['balienummer'] > 0) {
    $balienummer = $_SESSION['balienummer'];
    if (isset($_POST['opslaan'])) {

        if (empty($_POST['passagiernummer'])) {
            $fouten[] = 'Passagiernummer is niet ingevuld';
        } else {
            $passagiernummer = $_POST['passagiernummer'];
        }

        if (empty($_POST['naam'])) {
            $fouten[] = 'Naam is niet ingevuld';
        } else {
            $naam = $_POST['naam'];
        }

        if (empty($_POST['vluchtnummer'])) {
            $fouten[] = 'Vluchtnummer is niet ingevuld';
        } else {
            $vluchtnummer = $_POST['vluchtnummer'];
        }

        if (empty($_POST['geslacht'])) {
            $fouten[] = 'Geslacht is niet ingevuld';
        } else if ($_POST['geslacht'] != 'M' && $_POST['geslacht'] != 'V') {
            $fouten[] = 'Geslacht is niet M of V';
        } else {
            $geslacht = $_POST['geslacht'];
        }

        if (empty($_POST['stoel'])) {
            $fouten[] = 'Stoel is niet ingevuld';
        } else {
            $stoel = $_POST['stoel'];
        }

        if (empty($_POST['wachtwoord'])) {
            $fouten[] = 'Wachtwoord is niet ingevuld';
        } else {
            $wachtwoord = $_POST['wachtwoord'];
        }

        if (count($fouten) > 0) {
            $melding .= "";
            foreach ($fouten as $fout) {
                $melding .= "<p class='fouten'>$fout</p>";
            }
        } else {
            $hoogstePassagiernummerQuery = "SELECT TOP 1 passagiernummer FROM passagier ORDER BY passagiernummer DESC ";
            $prepNummer = $db->prepare($hoogstePassagiernummerQuery);
            $prepNummer->execute();
            $hoogstePassagiernummer = $prepNummer->fetch();

            $hoogstePassagiernummer = $hoogstePassagiernummer['passagiernummer'];
            $nieuwePassagiernummer = $hoogstePassagiernummer + 1;

            $maakNieuwePassagier = "INSERT INTO passagier (passagiernummer, naam, vluchtnummer, geslacht, balienummer, stoel, wachtwoord)
                                    VALUES (:passagiernummer, :naam, :vluchtnummer, :geslacht, :balienummer, :stoel, :wachtwoord)";
            $gegevens = $db->prepare($maakNieuwePassagier);
            $gegevens->execute([
                'passagiernummer' => $nieuwePassagiernummer,
                'naam' => $naam,
                'vluchtnummer' => $vluchtnummer,
                'geslacht' => $geslacht,
                'balienummer' => $balienummer,
                'stoel' => $stoel,
                'wachtwoord' => $wachtwoord
            ]);
            header("Location: Balie.php");
        }
    }
} 
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/normalize.css">
    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/Forum.css">
    <link rel="stylesheet" href="../../CSS/Medewerkerstyle.css">
    <link rel="stylesheet" href="../../CSS/Nieuwe-passagier.css">


    <title>Nieuwe Passagier</title>
</head>

<body>
    <?=MEDEWERKERHEADER?>
    <main>
        <section>
            <form action="" method="post">
                <h2>Passagier gegevens:</h2>

                <input type="hidden" name="passagiernummer" value="<?= $nieuwePassagiernummer ?>">

                <label for="naam">Naam:</label>
                <input type="text" id="naam" name="naam" placeholder="Hoofdletter">

                <label for="vluchtnummer">Vluchtnummer:</label>
                <input type="text" id="vluchtnummer" name="vluchtnummer" placeholder="Vluchtnummer">

                <label for="geslacht">Geslacht:</label>
                <input type="text" id="geslacht" name="geslacht">

                <label for="stoel">Stoel:</label>
                <input type="text" id="stoel" name="stoel" placeholder="Stoel">

                <label for="wachtwoord">Wachtwoord:</label>
                <input type="password" id="wachtwoord" name="wachtwoord" placeholder="Wachtwoord">
                <?= $melding ?>
                <input type="submit" id="knop" name="opslaan" value="opslaan">
            </form>
        </section>
    </main>

    <?= FOOTER ?>
</body>

</html>