<?php
require_once '../functies/db_connectie.php';
require_once '../functies/medewerkerHeader.php';
require_once '../functies/footer.php';
$db = maakVerbinding();
$gatecode = "";
$max_aantal = "";
$max_gewicht_pp = "";
$max_totaalgewicht = "";
$vertrektijd = "";
$maatschappijcode = "";
    
$melding = "";
$table = "";
$fouten = [];

$bestemmingOpties = "";
$maatschappijcodeOpties = "";
$gatecodeOpties = "";

if(!isset($_SESSION['balienummer'])){
    header('location: index.php');
    exit;
}

//opties aanmaken
$alleBestemmingen = "SELECT luchthavencode, naam FROM luchthaven";
$bestemmingPrep = $db->prepare($alleBestemmingen);
$bestemmingPrep->execute();
$bestemmingResult = $bestemmingPrep->fetchAll();

foreach ($bestemmingResult as $bestemming) {
    $bestemmingOpties.= "<option value='{$bestemming['luchthavencode']}'>{$bestemming['naam']}</option>";
}

$alleMaatschappijen = "SELECT maatschappijcode, naam FROM maatschappij";
$maatschappijPrep = $db->prepare($alleMaatschappijen);
$maatschappijPrep->execute();
$maatschappijResult = $maatschappijPrep->fetchAll();

foreach ($maatschappijResult as $maatschappij) {
    $maatschappijcodeOpties.= "<option value='{$maatschappij['maatschappijcode']}'>{$maatschappij['naam']}</option>";
}

$Allegatecodes = "SELECT gatecode FROM gate";
$gatecodePrep = $db->prepare($Allegatecodes);
$gatecodePrep->execute();
$gatecodeResult = $gatecodePrep->fetchAll();

foreach ($gatecodeResult as $gatecode) {
    $gatecodeOpties.= "<option value='{$gatecode['gatecode']}'>{$gatecode['gatecode']}</option>";
}

if (isset($_POST['opslaan'])) {
    $bestemming = trim(strip_tags($_POST['bestemming']));
    $gatecode = trim(strip_tags($_POST['gatecode']));
    $maxAantal = trim(strip_tags($_POST['max_aantal']));
    $maxGewichtPp = trim(strip_tags($_POST['max_gewicht_pp']));
    $maxTotaalGewicht = trim(strip_tags($_POST['max_totaalgewicht']));
    $vertrektijd = trim(strip_tags($_POST['vertrektijd']));
    $maatschappijcode = trim(strip_tags($_POST['maatschappijcode']));

    if (empty($bestemming)) {
        $fouten[] = 'bestemming vereist';
    }
    if (empty($gatecode)) {
        $fouten[] = 'Gate is vereist';
    }
    if (empty($maxAantal)) {
        $fouten[] = 'maximale aantal passagiers moet meegegeven worden';
    }
    if($maxAantal < 0 || $maxAantal > 100) {
        $fouten[] = 'tussen de 0 en 100 passagiers'; 
    }
    if (empty($maxGewichtPp)) {
        $fouten[] = 'maximale gewicht per persoon moet meegegeven worden';
    }
    if($maxGewichtPp > 35.00) {
        $fouten[] = 'maximaal 35.00 kg, als je meer dan 35 kg wilt ga dan in contact met de servicedesk'; 
    }
    if (empty($maxTotaalGewicht)) {
        $fouten[] = 'maximale gewicht moet we weten zodat de vliegtuig niet stort';
    }
    if($maxTotaalGewicht > 4000.00) {
        $fouten[] = 'Er past maximaal 4 ton aan bagage in een airbus'; 
    }
    if (empty($vertrektijd)) {
        $fouten[] = 'vertrektijd is vereist';
    }
    if(!empty($vertrektijd)){
        $fouten[] = 'vertrektijd is vereist';
    }
    if(strtotime($vertrektijd) < time ()) {
        $fouten[] = 'vertrektijd moet in de toekomst zijn';
    }

    if (empty($maatschappijcode)) {
        $fouten[] = 'maatschappijcode is vereist';
    }

    if (count($fouten) > 0) {
        $melding .= "<ul>";
        foreach ($fouten as $fout) {
            $melding .= "<li>" . $fout . "</li>";
        }
        $melding .= "</ul>";
    } else {
        $melding .= "geen fouten";

        $hoogsteVluchtnummer = "SELECT TOP 1 vluchtnummer FROM vlucht ORDER BY 1 DESC";

        $deNieuweVluchtnummer = $db -> prepare($hoogsteVluchtnummer);
        $deNieuweVluchtnummer ->execute();
        
        
        $deNieuweVluchtnummer = $deNieuweVluchtnummer->fetch();
        
        $vluchtnummer = $deNieuweVluchtnummer['vluchtnummer'] + 1;
        
        $maakVluchtQuery = "INSERT INTO Vlucht (vluchtnummer, bestemming, gatecode, max_aantal, max_gewicht_pp, max_totaalgewicht, vertrektijd, maatschappijcode)
                            VALUES (:vluchtnummer, :bestemming, :gatecode, :max_aantal, :max_gewicht_pp, :max_totaalgewicht, :vertrektijd, :maatschappijcode)";
        $deNieuweVlucht = $db -> prepare($maakVluchtQuery);
        $deNieuweVlucht ->execute([
            ':vluchtnummer' => $vluchtnummer, 
            ':bestemming' => $_POST['bestemming'],
            ':gatecode' => $_POST['gatecode'],
            ':max_aantal' => $_POST['max_aantal'], 
            ':max_gewicht_pp' => $_POST['max_gewicht_pp'],
            ':max_totaalgewicht' => $_POST['max_totaalgewicht'],
            ':vertrektijd' =>  (new DateTime($_POST['vertrektijd'])) -> format('Y-m-d\TH:i:s'),
            ':maatschappijcode' => $_POST['maatschappijcode']
        ]);

        header("location: ../vluchtdetails.php?vluchtnummer=$vluchtnummer");

        
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
    <link rel="stylesheet" href="../../CSS/Vluchtmaken.css">

    <title>Nieuwe Vlucht</title>
</head>

<body>
    <?=MEDEWERKERHEADER?>

    <main>

        <section>
            <form action="" method="post">
                <h2>vlucht toevoegen</h2>
                <label for="bestemming">Bestemming:</label>
                <select name="bestemming" id="bestemming" required>
                    <option value="" disabled selected>Kies een Bestemming</option>
                        <?= $bestemmingOpties ?>
                </select>

                <label for="gatecode">Gate:</label>
                <select name="gatecode" id="gatecode" required>
                    <option value="" disabled selected>Kies het juiste gatecode</option>
                    <?= $gatecodeOpties ?>
                </select>

                <label for="max_aantal:">max aantal passagiers</label>
                <input type="number" id="max_aantal" name="max_aantal" required>

                <label for="max_gewicht_pp:">max gewicht per passagiers</label>
                <input type="number" id="max_gewicht_pp" name="max_gewicht_pp" required>

                <label for="max_totaalgewicht:">max totaal gewicht</label>
                <input type="number" id="max_totaalgewicht" name="max_totaalgewicht" required>

                <label for="vertrektijd">vertrektijd:</label>
                <input type="datetime-local" id="vertrektijd" name="vertrektijd" required>

                <label for="maatschappijcode">Maatschappij:</label>
                <select name="maatschappijcode" id="maatschappijcode" required>
                    <option value="" disabled selected>Kies het juiste maatschappij</option>
                    <?= $maatschappijcodeOpties ?>
                </select>
                <br>

                <input type="submit" value="opslaan" id="opslaan" name="opslaan">
                <p><?= $melding  ?></p>
            </form>
        </section>
    </main>

    <?=FOOTER?>
</body>

</html>