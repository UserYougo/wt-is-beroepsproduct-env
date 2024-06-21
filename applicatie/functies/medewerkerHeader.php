<?php 
var_dump($_SESSION);
define('MEDEWERKERHEADER' ,maakHeaderMedewerker());
function maakHeaderMedewerker(){
    $html = '
    <header id="medewerker" >
        <h1>Airport Gerle</h1>
        <h2>Medewerker portaal</h2>
        <nav>
            <ul>
                <li><a href="Balie.php">Balie</a></li>
                <li><a href="passegiersVanVlucht.php">Passegiers van vlucht</a></li>
                <li><a href="CheckIn-medwerker.php"></a></li>
                <li><a href="Vlucht-maken.php">Nieuwe vlucht</a></li>
                <li><a href="Passagier-toevoegen.php">Nieuwe Passagier</a></li>
                <li><a href="../uitloggen.php">Uitloggen</a></li>
            </ul>
        </nav>
    </header>';
    return $html;
}
