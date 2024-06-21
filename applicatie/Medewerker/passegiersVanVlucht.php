<?php
session_start();
require_once '../functies/db_connectie.php';
require_once '../functies/medewerkerHeader.php';
require_once '../functies/footer.php';

$db = maakVerbinding();

$tabelPassegier = '';
$vluchtnummer ='';

if (isset($_GET['vluchtnummer'])) {
    if (is_numeric($_GET['vluchtnummer'])  && strlen($_GET['vluchtnummer']) == 5) {
        $vluchtnummer = (int) $_GET['vluchtnummer'];
        $sql_check_vluchtnummer = "SELECT vluchtnummer FROM Vlucht WHERE vluchtnummer = :vluchtnummer";
        $query_check = $db->prepare($sql_check_vluchtnummer);
        $query_check->bindParam(':vluchtnummer', $vluchtnummer, PDO::PARAM_INT);
        $query_check->execute();
        $result_check = $query_check->fetch();

        if ($result_check) {
            $sql_passegier =    "SELECT
                                v.bestemming, 
                                p.passagiernummer,
                                p.naam,
                                p.geslacht,
                                p.balienummer,
                                p.stoel,
                                p.inchecktijdstip,
                                b.stukkenbagage, 
                                b.totaal_gewicht
                            FROM 
                                Passagier p 
                            INNER JOIN 
                                Vlucht v ON p.vluchtnummer = v.vluchtnummer
                            INNER JOIN 
                                (SELECT 
                                    passagiernummer, 
                                    COUNT(*) AS stukkenbagage, 
                                    SUM(gewicht) AS totaal_gewicht 
                                FROM 
                                    bagageObject 
                                GROUP BY 
                                    passagiernummer) b ON p.passagiernummer = b.passagiernummer
                            WHERE 
                                p.vluchtnummer = :vluchtnummer";
            $query = $db->prepare($sql_passegier);
            $query->bindParam(':vluchtnummer', $vluchtnummer, PDO::PARAM_INT); // Use PDO::PARAM_INT to specify the parameter type
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                $tabelPassegier = "<ul class='grote-tabel' id='medewerker-vluchtentabel'>
                                <li><h3>bestemming</h3></li>
                                <li><h3>passagiernummer</h3></li>
                                <li><h3>naam</h3></li>
                                <li><h3>geslacht</h3></li>
                                <li><h3>balienummer</h3></li>
                                <li><h3>inchecktijdstip</h3></li>
                                <li><h3>stukkenbagage</h3></li>
                                <li><h3>totaal gewicht</h3></li>";
                foreach ($result as $lijst) {
                    $inchecktijdstip = date('m-d-y | H:i', strtotime($lijst['inchecktijdstip']));
                    $tabelPassegier .= "<li><p>{$lijst['bestemming']}</p></li>";
                    $tabelPassegier .= "<li><p>{$lijst['passagiernummer']}</p></li>";
                    $tabelPassegier .= "<li><p>{$lijst['naam']}</p></li>";
                    $tabelPassegier .= "<li><p>{$lijst['geslacht']}</p></li>";
                    $tabelPassegier .= "<li><p>{$lijst['balienummer']}</p></li>";
                    $tabelPassegier .= "<li><p>$inchecktijdstip</p></li>";
                    $tabelPassegier .= "<li><p>{$lijst['stukkenbagage']}</p></li>";
                    $tabelPassegier .= "<li><p>{$lijst['totaal_gewicht']}</p></li>";
                }
                $tabelPassegier .= "</ul>";
            } else {
                $tabelPassegier = "<p>Er zijn geen passagiers voor deze vlucht</p>";
            }
        } else {
            $tabelPassegier = "<p>Er zijn geen vluchten met dit vluchtnummer <p>";
        }
    } else {
        $tabelPassegier = "<p>foutief vluchtnummer format</p>";
    }
} else {
    $tabelPassegier = '<p>Geen gevens om te tonen zonder vluchtnummer</p>';
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
    <link rel="stylesheet" href="../../CSS/VluchtenTabel.css">
    <title>Medewerker-site</title>
</head>

<body>
    <?= MEDEWERKERHEADER ?>
    <main>
        <section>
            <div class="filteren">
                <form method="GET">
                    <h2>Vlucht zoeken</h2>
                    <label for="vluchtnummer">vluchtnummer:</label>
                    <input type="text" id="vluchtnummer" name="vluchtnummer" placeholder="#####" min="9999" max="99999">
                    <input type="submit" value="vluchtgegevens opzoeken" id="knop-vlucht">
                </form>
            </div>
            <h2>Alle passegiers van: <?= $vluchtnummer ?></h2>
            <?= $tabelPassegier ?>
        </section>
    </main>
    <?= FOOTER ?>
</body>

</html>