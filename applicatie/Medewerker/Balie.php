<?php
session_start();
require_once '../functies/db_connectie.php';
require_once '../functies/medewerkerHeader.php';
require_once '../functies/footer.php';

$db = maakVerbinding();
$balienummer = 0;
$fouten = [];
$melding = "";

if (!empty($_SESSION['balienummer'])) {
    $balienummer = $_SESSION['balienummer'];
} else {
    header('Location: ../index.php');
}

if ($balienummer > 0) {

    if (!empty($_GET['sorteren']) && !empty($_GET['orderDirection'])) {
        $sorteren = $_GET['sorteren'];
        $orderDirection = $_GET['orderDirection'];

        $gestorteerdQuery = "SELECT * FROM vlucht ORDER BY $sorteren $orderDirection";
        $gegevensVlucht = $db->prepare($gestorteerdQuery);
        $gegevensVlucht->execute();
        if ($rij = $gegevensVlucht->fetch()) {
        }
    } else if (isset($_GET['likeGetallen'])) {
        $filterGetal = "%" . $_GET['likeGetallen'] . "%";
        $filteredQueryVluchtnummer = "SELECT * 
                                    FROM vlucht 
                                    WHERE vluchtnummer 
                                    like :getal";

        $gegevensVlucht = $db->prepare($filteredQueryVluchtnummer);
        $gegevensVlucht->execute(['getal' => $filterGetal]);
    } else {
        $gegevensOphalenVluchtQuery = "SELECT * FROM vlucht";
        $gegevensVlucht = $db->prepare($gegevensOphalenVluchtQuery);
        $gegevensVlucht->execute();
    }
    $tabelVluchtgegevens = "<ul class='grote-tabel' id='medewerker-vluchtentabel'>
                            <li><h3>vluchtnummer</h3></li>
                            <li><h3>bestemming</h3></li>
                            <li><h3>gatecode</h3></li>
                            <li><h3>Max personen</h3></li>
                            <li><h3>Max gewicht PP</h3></li>
                            <li><h3>Max totaalgewicht</h3></li>
                            <li><h3>Vertrekt</h3></li>
                            <li><h3>Maatschappijcode</h3></li>";

    foreach ($gegevensVlucht as $lijst) {
        $vertrektijd = date('m-d-y | H:i', strtotime($lijst['vertrektijd']));

        $tabelVluchtgegevens .= "<li><p><a href='../vluchtdetails.php?vluchtnummer={$lijst['vluchtnummer']}'>{$lijst['vluchtnummer']}</a></p></li>";
        $tabelVluchtgegevens .= "<li><p>{$lijst['bestemming']}</p></li>";
        $tabelVluchtgegevens .= "<li><p>{$lijst['gatecode']}</p></li>";
        $tabelVluchtgegevens .= "<li><p>{$lijst['max_aantal']}</p></li>";
        $tabelVluchtgegevens .= "<li><p>{$lijst['max_gewicht_pp']}</p></li>";
        $tabelVluchtgegevens .= "<li><p>{$lijst['max_totaalgewicht']}</p></li>";
        $tabelVluchtgegevens .= "<li><p>$vertrektijd</p></li>";
        $tabelVluchtgegevens .= "<li><p>{$lijst['maatschappijcode']}</p></li>";
    }

    $tabelVluchtgegevens .= "</ul>";

    $tabelVluchtgegevens .= "</ul>";
} else {
    $homeLijst = "<p>U bent niet ingelogd</p>";
    header("Location: ../homepagina.php");
}

$melding .= "<ul>";
foreach ($fouten as $fout) {
    $melding .= "<li>" . $fout . "</li>";
}
$melding .= "</ul>";

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/normalize.css">
    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/Forum.css">
    <link rel="stylesheet" href="../../CSS/VluchtenTabel.css">
    <title>Medewerker-site</title>
</head>

<body>

    <?= MEDEWERKERHEADER ?>

    <main>
        <div class="filteren">
            <form action="../Vluchtdetails.php?" method="GET">
                <h2>Vlucht zoeken</h2>
                <label for="vluchtnummer">vluchtnummer:</label>
                <input type="text" id="vluchtnummer" name="vluchtnummer" placeholder="5 getallen" min="9999" max="99999">
                <input type="submit" value="vluchtgegevens opzoeken" id="knop-vlucht">
            </form>
        </div>
        <div>
            <form action="" method="GET">
                <?= $melding ?>
                <label for="sorteren">Sorteren op</label>
                <select name="sorteren" id="sorteren" required>
                    <option value="" disabled selected>Kies waarop je wilt sorteren</option>
                    <option value="vertrektijd">vertrektijd</option>
                    <option value="luchthaven"> luchthaven</option>
                </select>
                <label for="orderDirection"></label>
                <input type="radio" name="orderDirection" value="ASC" checked> Oplopend
                <input type="radio" name="orderDirection" value="DESC"> Aflopend
                <input type="submit" value="sorteren" id="sorteren">
            </form>
        </div>
        </section>
        <section>
            <h2>Alle vluchten</h2>
            <?= $tabelVluchtgegevens ?>
        </section>
    </main>

    <?= FOOTER ?>
</body>

</html>