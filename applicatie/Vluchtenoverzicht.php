<?php
session_start();
require_once 'functies/db_connectie.php';
require_once 'functies/header.php';
require_once 'functies/footer.php';
require_once 'functies/vluchtenFuncties.php';


$db = maakVerbinding();
$dagen = 30;
$gegevensVluchten = vluchtGegevensAankomendeAantalUur($dagen, 'dag'); //kan nog dropdown voor maken
$vluchtenTabel = genereerHTMLtabelVluchten($gegevensVluchten);

//header('location: Vluchtdetails.php?vluchtnummer=' . $vluchtnummer);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/normalize.css">
    <link rel="stylesheet" href="../CSS/Style.css">
    <link rel="stylesheet" href="../CSS/VluchtenTabel.css">
    <title>vluchtenoverzicht</title>
</head>

<body>
    <?= HEADER ?>
    <main>
        <section id="vluchten">
            <h2>Vertrekkende vluchten voor de volgende <?= $dagen ?> dagen:</h2>
            <?= $vluchtenTabel ?>
            <br>
            <form action='Vluchtdetails.php' method="get">
            <label>vluchtnummer invoeren</label>
            <input type="text" id="vluchtnummerzoeken" name="vluchtnummer" required>
            <input type="submit" value="zoeken" id="zoeken">
        </form>;
        </section>
        
    </main>
    <?= FOOTER ?>
</body>

</html>