<?php
require_once("db_connectie.php");

$passagiernaam = '';
If(isset($_SESSION['passagiernummer'])){
    If(!isset($_SESSION['passagiernaam'])){
        $sql_naamOphalen = "SELECT naam FROM passagier WHERE passagiernummer = :passagiernummer";
        $querry = $db->prepare($sql_naamOphalen);
        $querry->bindParam(':passagiernummer', $_SESSION['passagiernummer']);
        $querry->excecute();
        $result = $querry->fetchRow(PDO::FETCH_ASSOC);

        $_SESSION['passagiernaam'] = $result['naam'];
        $passagiernaam = $_SESSION['passagiernaam'];   
    } else {
        $passagiernaam = $_SESSION['passagiernaam'];
    }
}

define("HEADER", maakHeader($passagiernaam));


function maakHeader($passagiernaam){
    $header = '
    <header>
        <h1>Gelder Airport</h1>
        <h2> Welkom ' . $passagiernaam . ' </h2>
        <nav>
            
            <ul>
            <li><a href="#">Vlucht</a></li>
            <li><a href="#">Data</a></li>
            <li><a href="#">Login</a></li>
            </ul>
        </nav>
    </header>
    ';
return $header;
}
?>