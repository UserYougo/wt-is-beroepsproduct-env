<?php 
require_once '../functies/medewerkerHeader.php';
require_once '../functies/footer.php';

if(!isset($_SESSION['balienummer'])){
    header('location: index.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/normalize.css">
    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/Medewerkerstyle.css">
    <link rel="stylesheet" href="../../CSS/Forum.css">

    <title>Check-in</title>
</head>
<body>
    <?= MEDEWERKERHEADER ?>
    
    <main>
        <section>
            <form action="../Passagier-ingecheckt.php" method="get">
                <h2>Passagier inchecken</h2>
                <label for="vluchtnummer">vluchtnummer:</label>
                <input type="text" id="vluchtnummer" name="vlucht" placeholder="bijvoorbeeld: AB1234" required pattern="[A-Za-z]{2}[0-9]{4}">
                <label for="bsn">BSN-nummer:</label>
                <input type="text" id="bsn" name="bsn-nummer" placeholder="voor bevestiging" >
                <label for="email">e-mail:</label>
                <input type="email" id="email" name="email-adress" placeholder="voor bevestiging" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[0-9])(?=.*?[!@#$%^&*+`~=?\|<>/]).{8,}">
                <input type="submit" value="Inchecken voor deze vlucht" id="knop-vlucht">
            </form>
        </section>

        <section>
            <form action="../Bagage-ingecheckt.php" method="get">
                <h2>Bagage inchecken</h2>
                <label for="vluchtnummer">vluchtnummer:</label>
                <input type="text" id="Bagage-vluchtnummer" name="vluchtnummer voor bagage" placeholder="bijvoorbeeld: AB1234" required pattern="[A-Za-z]{2}[0-9]{4}">
                <label for="gewicht">Gewicht van Bagage:</label>
                <input type="number" id="gewicht" name="gewicht" placeholder="in grammen (max 35kg)" max="35000" >
                <input type="submit" value="Bagage toevoegen" id="knop-Bagage">
            </form>
        </section>

    </main>

    <?= FOOTER ?>
</body>
</html>