<?php
session_start();
require_once 'functies/db_connectie.php';
require 'functies/header.php';
require_once 'functies/footer.php';

$db = maakVerbinding();

var_dump($_POST);
$melding = "";
$detailsVlucht = "";
$bestemming = "";
$maatschappij = "";
$titel = "Airport Gerle";
$ondertitel = "";
$linkTerug = "";

if (isset($_POST['vluchtnummer'])) {
    $vluchtnummer = $_POST['vluchtnummer'];
}else{
    $melding = "gefaald met vluchtnummer ophalen";
}

function getFlightDetails($db, $query, $parameters) {
    $statement = $db->prepare($query);
    $statement->execute($parameters);

    if ($result = $statement->fetch()) {
        return $result;
    } else {
        return null;
    }
}

if (isset($_SESSION['balienummer'])) {
    $balienummer = $_SESSION['balienummer'];
    $linkTerug = "Medewerker-sites/Balie.php";
} else {
    $linkTerug = "Vluchtenoverzicht.php";
}

if (isset($_POST['vluchtnummer'])) {
    $vluchtnummer = $_POST['vluchtnummer'];

    $vluchtDetailsQuery = "SELECT * FROM vlucht WHERE vluchtnummer = :vluchtnummer";
    $detailResult = getFlightDetails($db, $vluchtDetailsQuery, ['vluchtnummer' => $vluchtnummer]);

    if (!$detailResult) {
        $melding = "Vluchtnummer niet gevonden";
        $titel = "Airport Gerle";
        $ondertitel = "Vluchtdetails/vluchtnummer niet gevonden";
    } else {
        $titel = $vluchtnummer;
        $ondertitel = "Vluchtdetails van Airport Gerle";

        $vluchtBestemmingQuery = "SELECT m.naam as naam 
        FROM Vlucht v 
        LEFT JOIN maatschappij m 
        ON v.maatschappijcode = 
        m.maatschappijcode 
        WHERE vluchtnummer = 28761";
        $bestemmingResult = getFlightDetails($db, $vluchtBestemmingQuery, ['vluchtnummer' => $vluchtnummer]);

        if ($bestemmingResult) {
            $bestemming .= $bestemmingResult['naam'] . " (" . utf8_decode($bestemmingResult['land']) . ")";
        } else {
            $melding .= "fout, kon niet de bestemming ophalen";
        }

        $vluchtMaatschappijQuery = "SELECT m.naam as naam FROM Vlucht v LEFT JOIN maatschappij m ON v.maatschappijcode = m.maatschappijcode WHERE vluchtnummer = :vluchtnummer";
        $maatschappijResult = getFlightDetails($db, $vluchtMaatschappijQuery, ['vluchtnummer' => $vluchtnummer]);

        if ($maatschappijResult) {
            $maatschappij .= $maatschappijResult['naam'];
        } else {
            $melding .= "Maatschappij onleesbaar";
        }

        $detailsVlucht .= '<li><h3>vluchtnummer</h3></li>...';  // Include other details similarly
        $detailsVlucht .= "<li><p>" . $detailResult['vluchtnummer'] . "</p></li>";
        $detailsVlucht .= "<li><p>" . $detailResult['bestemming'] . "</p></li>";
        $detailsVlucht .= "<li><p>" . $detailResult['gatecode'] . "</p></li>";
        $detailsVlucht .= "<li><p>" . $detailResult['max_aantal'] . "</p></li>";
        $detailsVlucht .= "<li><p>" . $detailResult['max_gewicht_pp'] . "</p></li>";
        $detailsVlucht .= "<li><p>" . $detailResult['max_totaalgewicht'] . "</p></li>";
        $vertrektijd = date('m-d-y|H:i', strtotime($detailResult['vertrektijd']));
        $detailsVlucht .= "<li><p>" . $vertrektijd . "</p></li>";
        $detailsVlucht .= "<li><p>" . $detailResult['maatschappijcode'] . "</p></li>";
    }
} else {
    $melding = "gefaald met vluchtnummer ophalen";
}
?>  

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/normalize.css">
    <link rel="stylesheet" href="../CSS/Style.css">

    <title>Inchecken</title>
</head>
<body>
    <?=HEADER ?>

    <main id="details">
        <section>
            <ul class="grote-tabel" id="detail-tabel">
                <?= $detailsVlucht ?>
            </ul>
            <p><a href="<?= $linkTerug ?>">druk hier om terug te keren naar de vluchtenoverzicht</a></p>
        </section>
    </main>

<?= FOOTER ?>
</body>
</html>