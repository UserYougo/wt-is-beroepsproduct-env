<?php
require_once("db_connectie.php");


//var_dump($_SESSION);
$passagiernaamWelkom = '';


If(isset($_SESSION['passagiernummer'])){
    If(!isset($_SESSION['passagiernaam'])){
        $db = maakVerbinding();
        $sql_naamOphalen = "SELECT naam FROM passagier WHERE passagiernummer = :passagiernummer";
        $querry = $db->prepare($sql_naamOphalen);
        $querry->bindParam(':passagiernummer', $_SESSION['passagiernummer']);
        $querry->execute();
        $result = $querry->fetch(PDO::FETCH_ASSOC);

        $_SESSION['passagiernaam'] = $result['naam'];
        
    }
    $passagiernaamWelkom = '<h2> Goed u weer te zien ' . $_SESSION['passagiernaam'] . '</h2>';   
    $inloggen = "<li><a href='inchecken.php'>Inchecken</a></li>
                <li><a href='uitloggen.php'>Uitloggen</a></li>";
} else {
    $inloggen = "
                 <li><a href='inloggen.php'>Inloggen</a></li>";
}

define("HEADER", maakHeader($passagiernaamWelkom, $inloggen));

function maakHeader($passagiernaamWelkom, $inloggen){
    $header = '
    <header>
        <h1>Gelder Airport</h1>
        ' . $passagiernaamWelkom . '
        <nav>
            <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="Vluchtenoverzicht.php">Vluchten</a></li>
            <li><a href="Vluchtdetails.php">Vlucht details</a></li>
            ' . $inloggen . '
            </ul>
        </nav>
    </header>
    ';
return $header;
}
?>