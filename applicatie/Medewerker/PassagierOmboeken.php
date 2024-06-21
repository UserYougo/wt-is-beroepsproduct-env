<?php
require_once 'functies/db_connectie.php';
require_once 'functies/header.php';
require_once 'functies/footer.php';
$db = maakVerbinding();

if (isset($_POST['passagierNr']) && isset($_POST['vluchtnummer'])) {
    $passagierNr = trim(strip_tags($_POST['passagierNr']));
    $vluchtnummer = trim(strip_tags($_POST['vluchtnummer']));
    if ((is_numeric($passagierNr) && strlen($passagierNr) == 5) && (is_numeric($vluchtnummer) && strlen($vluchtnummer))) {
        $sqlallePassagierNr = "SELECT passagiernummer FROM Passagier WHERE passagiernummer = :passagierNr";
        $query_check = $db->prepare($sqlallePassagierNr);
        $query_check->bindParam(':passagierNr', $passagierNr, PDO::PARAM_INT);
        $query_check->execute();
        $gevondenPassagier = $query_check->fetch();

        $sqlVlucht = "SELECT vluchtnummer FROM Vlucht WHERE vluchtnummer = :vluchtnummer";
        $query_check_vlucht = $db->prepare($sqlallePassagierNr);
        $query_check_vlucht->bindParam(':vluchtnummer', $vluchtnummer, PDO::PARAM_INT);
        $query_check_vlucht->execute();
        $gevondenVlucht = $query_check->fetch();

        if ($gevondenPassagier && $gevondenVlucht) {
            $sqlStoelNietBezet = "SELECT passagiernummer as count
            FROM Passagier
            WHERE vluchtnummer = :vluchtnummer
            AND stoel IN (
              SELECT stoel
              FROM Passagier
              WHERE passagiernummer = passagierNr
              AND vluchtnummer = :vluchtnummer)";;
            $query_check_StoelNietBezet = $db->prepare($sqlStoelNietBezet);
            $query_check_StoelNietBezet->bindParam(':passagierNr', $vluchtnummer, PDO::PARAM_INT);
            $query_check_StoelNietBezet->bindParam(':vluchtnummer', $vluchtnummer, PDO::PARAM_INT);
            $gevondenIemandal = $query_check->fetch();
            if (empty($gevondenIemandal)) {
                $sqlUpdate = "UPDATE Passagier SET vluchtnummer = :vluchtnummer WHERE passagiernummer = :passagierNr";
                $query_check_vlucht = $db->prepare($sqlUpdate);
                $query_check_vlucht->bindParam(':passagierNr', $vluchtnummer, PDO::PARAM_INT);
                $query_check_vlucht->bindParam(':vluchtnummer', $vluchtnummer, PDO::PARAM_INT);
                $query_check_vlucht->execute();
            }
        }
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
    <link rel="stylesheet" href="../CSS/VluchtenTabel.css">
    <title>Passagier Omboeken</title>
</head>

<body>
    <?= MEDEWERKERHEADER ?>
    <main>
        <section>
            <div class="filteren">
                <form method="post">
                    <label>Passagier zoeken</label>
                    <input type="text" id="passagierNr" name="passagierNr" placeholder="Passagiernummer">

                    <label>Vluchtnummer</label>
                    <input type="text" id="vluchtnummer" name="vluchtnummer" placeholder="Passagiernummer">

                    <input type="submit" value="vervang" name="vervang" id="knop-vlucht">
                </form>
            </div>
            <h2>Passagier gegevens</h2>
            <?= $tabelPassagier ?>
        </section>
    </main>
    <?= FOOTER ?>
</body>

</html>