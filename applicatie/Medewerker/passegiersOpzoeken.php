<?php
session_start();
require_once '../functies/db_connectie.php';
require_once '../functies/medewerkerHeader.php';
require_once '../functies/footer.php';


$db = maakVerbinding();

$tabelPassagier = '';

if (isset($_GET['passagierNr'])) {
    $passagierNr = trim($_GET['passagierNr']);
    if (is_numeric($passagierNr) && strlen($passagierNr) == 5) {
        $sqlallePassagierNr = "SELECT passagiernummer FROM Passagier WHERE passagiernummer = :passagierNr";
        $query_check = $db->prepare($sqlallePassagierNr);
        $query_check->bindParam(':passagierNr', $passagierNr, PDO::PARAM_INT);
        $query_check->execute();
        $gevonden = $query_check->fetch();
        if ($gevonden) {
            $sql_passegier = "SELECT * FROM Passagier WHERE passagiernummer = :passagiernummer";
            $query = $db->prepare($sql_passegier);
            $query->bindParam(':passagiernummer', $passagierNr, PDO::PARAM_INT); // Use PDO::PARAM_INT to specify the parameter type
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $tabelPassagier = "<ul class='grote-tabel' id='medewerker-passagiertabel'>                                
                                <li><h3>passagiernummer</h3></li>
                                <li><h3>naam</h3></li>
                                <li><h3>vluchtnummer</h3></li>
                                <li><h3>geslacht</h3></li>
                                <li><h3>balienummer</h3></li>
                                <li><h3>inchecktijdstip</h3></li>";
            foreach ($result as $lijst) {
                $inchecktijdstip = date('m-d-y | H:i', strtotime($lijst['inchecktijdstip']));
                $tabelPassagier .= "<li><p>{$lijst['passagiernummer']}</p></li>";
                $tabelPassagier .= "<li><p>{$lijst['naam']}</p></li>";
                $tabelPassagier .= "<li><p>{$lijst['vluchtnummer']}</p></li>";
                $tabelPassagier .= "<li><p>{$lijst['geslacht']}</p></li>";
                $tabelPassagier .= "<li><p>{$lijst['balienummer']}</p></li>";
                $tabelPassagier .= "<li><p>$inchecktijdstip</p></li>";
            }
            $tabelPassagier .= "</ul>";
        } else {
            $tabelPassagier = '<p> Geen passagier gevonden met dat passagiernummer</p>';
        }
    } else {
        $tabelPassagier = '<p> Incorrecte passagiernummer fromat</p>';
    }
} else{
    $tabelPassagier = '<p> Geen gevensv om te tonen zonder passagiernummer</p>';
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
                <h2>Passagier zoeken</h2>
                <input type="text" id="passagierNr" name="passagierNr" placeholder="Passagiernummer">
                <input type="submit" value="vluchtgegevens opzoeken" id="knop-vlucht">
            </form>            
        </div>    
        <h2>Passagier gegevens</h2>
            <?= $tabelPassagier ?>
        </section>
    </main>
    <?= FOOTER ?>
</body>

</html>