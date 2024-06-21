<?php
session_start();
require_once 'functies/db_connectie.php';
require_once 'functies/header.php';
require_once 'functies/footer.php';


$db = maakVerbinding();

$fouten = [];
$gewicht = "";
$melding = "";
$passagiernummerVragen = "";

if (!isset($_SESSION['passagiernummer'])) {
    $passagiernummerVragen = "<label for='passagiernummer'>passagiernummer:</label>" .
        "<input type='text' id='vluchtnummer' name='vluchtnummer'>";
} else {
    $passagiernummerVragen = "<label for='passagiernummer'>passagiernummer:</label>" .
        "<input type='text' id='vluchtnummer' name='vluchtnummer' value='". $_SESSION['passagiernummer'] . "' readonly>";
}

if (isset($_POST['bagage'])) {
    if (empty($_POST['vluchtnummer'])) {
        $fouten[] = 'vluchtnummer verplicht';
    } else {
        $vluchtnummer = $_POST['vluchtnummer'];
    }

    if (!empty($_POST['passagiernummer']) && !empty($_SESSION['passagiernummer'])) {
        $fouten[] = "Vul iets in";
    } else if (!empty($_POST['passagiernummer'])) {
        $passagiernummer = $_POST['passagiernummer'];
    } else if (!empty($_SESSION['passagiernummer'])) {
        $passagiernummer = $_SESSION['passagiernummer'];
    }

    if (empty($_POST['gewicht'])) {
        $fouten[] = 'gewicht is verplicht';
    } else {
        $gewicht = $_POST['gewicht'];
    }

    if (isset($vluchtnummer) && !count($fouten) > 0) {
        $checkGewichtQuery = "SELECT max_gewicht_pp AS maxGewicht FROM vlucht WHERE vluchtnummer = :vluchtnummer";
        $maxBerekenen = $db->prepare($checkGewichtQuery);
        $maxBerekenen->execute(['vluchtnummer' => $vluchtnummer]);
        $maxGewicht = $maxBerekenen->fetch();
        $maxGewicht = $maxGewicht['maxGewicht'];
    }

    if (isset($passagiernummer) && !count($fouten) > 0) {
        $gewichtHuidig = "SELECT gewicht FROM bagageObject WHERE passagiernummer = :passagiernummer";
        $huidigeGewicht = $db->prepare($gewichtHuidig);
        $huidigeGewicht->execute(['passagiernummer' => $passagiernummer]);
        $huidigeGewicht = $huidigeGewicht->fetch();
        $huidigeGewicht = $huidigeGewicht['gewicht'];
    }

    if (isset($maxGewicht) && isset($huidigeGewicht) && !count($fouten) > 0) {
        if ($maxGewicht < $huidigeGewicht + $gewicht) {
            $fouten[] = "Je hebt te veel gewicht";
        }
    }

    if (count($fouten) > 0) {
        $melding .= "<ul>";
        foreach ($fouten as $fout) {
            $melding .= "<li>" . $fout . "</li>";
        }
        $melding .= "</ul>";
    } else {
        $melding .= "geen fouten";

        $updateGewicht = "UPDATE bagageObject SET gewicht = :gewicht WHERE passagiernummer = :passagiernummer";
        $nieuwGewicht = $db->prepare($updateGewicht);
        $nieuwGewicht->execute(['gewicht' => $huidigeGewicht + $gewicht, 'passagiernummer' => $passagiernummer]);

        $objectvolgnummerQuery = "SELECT objectvolgnummer FROM bagageObject WHERE passagiernummer = :passagiernummer";
        $objectvolgnummerOphalen = $db->prepare($objectvolgnummerQuery);
        $objectvolgnummerOphalen->execute(['passagiernummer' => $passagiernummer]);
        $objectvolgnummer = $objectvolgnummerOphalen->fetch();
        $objectvolgnummer = $objectvolgnummer['objectvolgnummer'];

        $bagageUpdateQuery = "UPDATE bagageObject 
                              SET objectvolgnummer = :objectvolgnummer WHERE passagiernummer = :passagiernummer";
        $bagageUpdate = $db->prepare($bagageUpdateQuery);
        $bagageUpdate->execute([
            ':objectvolgnummer' => $objectvolgnummer + 1,
            ':passagiernummer' => $passagiernummer
        ]);
        $_SESSION['passagiernummer'] = $passagiernummer;
        header("location: ingecheckt.php");
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

    <title>Chech-in</title>
</head>

<body>
    <?= HEADER ?>
    <main>
        <section>
            <form action="" method="post">
                <h2>Bagage inchecken</h2>
                <?= $passagiernummerVragen ?>
                <label for="vluchtnummer">vluchtnummer:</label>
                <input type="number" id="vluchtnummer" name="vluchtnummer">
                <?= $melding ?>
                <label for="gewicht">Gewicht van Bagage:</label>
                <input type="number" id="gewicht" name="gewicht" placeholder="(max 35kg)">
                <input type="submit" value="bagage" id="bagage" name="bagage">
            </form>
        </section>
    </main>

    <?= FOOTER ?>
</body>

</html>