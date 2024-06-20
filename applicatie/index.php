<?php
session_start();
require_once 'PHP_defaultIncludes/db_connectie.php';
require_once 'PHP_defaultIncludes/header.php';
require_once 'PHP_defaultIncludes/footer.php';
require_once 'functies/vluchtenFuncties.php';

//$aankomdeVluchten = vluchtGegevensAankomendeAantalUur(24);
$test = test();

$vluchtenTabel = "<tabel>"
?>


<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <title>Gelder Airport</title>

    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="/CSS/normalize.css">
    <link rel="stylesheet" href="/CSS/homepagina.css">
</head>

<body>
    <?= HEADER ?>
    <main>
    <table>
    <tr><th>Vluchtnummer</th><th>Vertrektijd</th><th>Gate</th></tr>
    <td><a href='#'>" . $rij['Vluchtnummer'] . "</a></td>
    <td>". $rij['Vertrektijd']. "</td>
    <td>". $rij['Gate']. "</td>
    </tr>
    </table>
    </main>
   <?= FOOTER ?>
</body>

</html>