<?php 
session_start();
require_once 'functies/db_connectie.php';
require_once 'functies/header.php';
require_once 'functies/footer.php';


$db= maakVerbinding();
$melding = "";
$detailsVlucht = "";
$bestemming = "";
$maatschappij = "";
$vluchtnummer = '';
$vluchtnummerveld = '';
$linkterug = "";

if(isset($_SESSION['balienummer'])){
    $balienummer = $_SESSION['balienummer'];
    $linkterug = "Medewerker/Balie.php";
}else{
    $linkTerug = "Vluchtenoverzicht.php";
}

if(isset($_GET['vluchtnummer'])){
    if(is_numeric($_GET['vluchtnummer'])){
        $vluchtnummer = $_GET['vluchtnummer'];
        $vluchtDetailsQuery = "SELECT * FROM vlucht WHERE vluchtnummer = :vluchtnummer";
        $detailPrep = $db->prepare($vluchtDetailsQuery);
        $detailPrep->bindParam('vluchtnummer', $vluchtnummer);
        $detailPrep->execute();
    } else {
        header('location: Vluchtdetails.php');
    }


    if(!$detail = $detailPrep -> fetch()){
        $melding = "Vlucht niet gevonden";
    }else{
        $vluchtBestemming = "SELECT L.naam as naam, l.land as land FROM Vlucht v left join Luchthaven l on v.bestemming = l.luchthavencode WHERE vluchtnummer = :vluchtnummer";
        $detailBestemming = $db->prepare($vluchtBestemming);
        $detailBestemming->execute(['vluchtnummer' => $vluchtnummer]);
        
        if(!$bestemmingNaam = $detailBestemming -> fetch()){
            $melding .= "fout, kon niet de bestemming ophalen";
        } else {
            $bestemming .= $bestemmingNaam['naam'];
            $bestemming .= " (";
            $bestemming .= utf8_decode($bestemmingNaam['land']);
            $bestemming .= ") ";
        }
        $vluchtMaatschappij = "SELECT m.naam as naam FROM Vlucht v left join maatschappij m on v.maatschappijcode = m.maatschappijcode WHERE vluchtnummer = :vluchtnummer";
        $detailMaatschappij = $db->prepare($vluchtMaatschappij);
        $detailMaatschappij->execute(['vluchtnummer' => $vluchtnummer]);
        if(!$maatschappijNaam = $detailMaatschappij -> fetch()){
            $melding .= "Maatschappij onleesbaar";
        } else {
            $maatschappij .= $maatschappijNaam['naam'];
        }
        $detailsVlucht .= '<li><h3>vluchtnummer</li></h3><li><h3>bestemming</li></h3><li><h3>gatecode</li></h3><li><h3>max_aantal</li></h3><li><h3>max_gewicht_pp</li></h3><li><h3>max_totaalgewicht</li></h3><li><h3>vertrektijd</li></h3><li><h3>maatschappijcode</li></h3>';
        $detailsVlucht .= "<li><p>". $detail['vluchtnummer']. "</p></li>";
        $detailsVlucht .= "<li><p>". $detail['bestemming']. "</p></li>";
        $detailsVlucht .= "<li><p>". $detail['gatecode']. "</p></li>";
        $detailsVlucht .= "<li><p>". $detail['max_aantal']. "</p></li>";
        $detailsVlucht .= "<li><p>". $detail['max_gewicht_pp']. "</p></li>";
        $detailsVlucht .= "<li><p>". $detail['max_totaalgewicht']. "</p></li>";
        $vertrektijd = date('m-d-y|H:i', strtotime($detail['vertrektijd']));
        $detailsVlucht .= "<li><p>". $vertrektijd. "</p></li>";
        $detailsVlucht .= "<li><p>". $detail['maatschappijcode']. "</p></li>";
    }
    $melding = "<h2>Vlucht naar $bestemming met $maatschappij : <strong>$vluchtnummer</strong></h2>";
    $vluchtnummerveld = '<p><a href="Vluchtdetails.php">Druk hier om een andere vlucht op te zoeken</a></p>';
} else {
    $vluchtnummerveld = '<h2>U moet een vluchtnummer ingeven geven als u meer informatie wilt</h2>
    <form method="get">
    <label>vluchtnummer invoeren</label>
    <input type="text" id="vluchtnummerzoeken" name="vluchtnummer" required>
    <input type="submit" value="zoeken" id="zoeken">
    </form>';
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
    <title>Details <?= $vluchtnummer?></title>
</head>

<body>
    <?= HEADER ?>
    <main id="details">
            <section>
                <?=$melding ?>
                <?=$vluchtnummerveld?>
                <ul class="grote-tabel" id="detail-tabel">
                    <?= $detailsVlucht?>
                </ul>
                <p><a href="<?= $linkTerug ?>">Druk hier om terug te keren naar de vluchtenoverzicht</a></p>
                
            </section>
    </main>
<?= FOOTER ?>
</body>
</html>