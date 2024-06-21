<?php
session_start();
require_once 'functies/db_connectie.php';
require_once 'functies/header.php';
require_once 'functies/footer.php';
require_once 'functies/vluchtenFuncties.php';
$db = maakVerbinding();

$uren = 24;
$gegevensVluchten = vluchtGegevensAankomendeAantalUur($uren, 'uur'); //kan nog dropdown voor maken
$vluchtenTabel = genereerHTMLtabelVluchten($gegevensVluchten);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/normalize.css">
    <link rel="stylesheet" href="../CSS/Style.css">
    <link rel="stylesheet" href="../CSS/VluchtenTabel.css">

    <title>Homepagina GerleAirport</title>
</head>

<body>
    <?= HEADER ?>
    <main>
        <h1>Welkom op de homepagina</h1>
        <section id="vluchten">
            <h2>Vertrekkende vluchten voor aankomende <?= $uren ?> uur</h2>
            <?= $vluchtenTabel ?>
            <h3>Voor alle vluchten druk <a href="Vluchtenoverzicht.php"> <strong>Hier </strong></a></h3>
        </section>
    </main>
    <?= FOOTER ?>
</body>

</html>