<?php
session_start();
require_once '../functies/db_connectie.php';
require_once '../functies/medewerkerHeader.php';
require_once '../functies/footer.php';

$db = maakVerbinding(); 




$tabelPassegier = '<table>';
$tabelPassegier.=     '<tr>   
                <th>Vertrektijd</th>
                <th>Luchthavencode</th>
                <th>Vluchtnummer</th>
                <th>Gate</th>
                <th>Luchtvaartmaatschappij</th>
            </tr>';
foreach ($lijst as $row) {
    $tabelPassegier.= '<tr>';
    $tabelPassegier.= '<td>'. implode('</td><td>', $row). '</td>';
    $tabelPassegier.= '</tr>';
}
$tabelPassegier.= '</table>';
return $tabelPassegier;

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
                <form action="../Vluchtdetails.php?" method="GET">
                    <h2>Vlucht zoeken</h2>
                    <label for="vluchtnummer">vluchtnummer:</label>
                    <input type="text" id="vluchtnummer" name="vluchtnummer" placeholder="5 getallen" min="9999" max="99999">
                    <input type="submit" value="vluchtgegevens opzoeken" id="knop-vlucht">
                </form>
            </div>
            <h2>Alle vluchten</h2>
            <?= $$tabelPassegier ?>
        </section>
    </main>

    <?= FOOTER ?>
</body>

</html>