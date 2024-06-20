<?php
require_once 'PHP_defaultIncludes/db_connectie.php';

function test(){
    $db = maakVerbinding();
    $sql_test = "SELECT * FROM Gate";
    $querry = $db->prepare($sql_test);
    $querry->execute();
	
}
function vluchtGegevensAankomendeAantalUur($aantalUur)
{
    $db = maakVerbinding();
    $sql_Vluchtgegevens = "SELECT 
    v.VLUCHTNUMMER AS Vluchtnummer, 
    l.naam, 
    l.land, 
    m.naam, 
    FORMAT (vertrektijd, 'HH:mm') Vertrektijd, gatecode Gate 
	FROM Vlucht v
	LEFT JOIN Maatschappij m  ON v.maatschappijcode = m.maatschappijcode 
	LEFT JOIN Luchthaven l ON v.bestemming = l.luchthavencode
	WHERE vertrektijd 
            BETWEEN (DATEADD(HOUR, 2, GETDATE())) AND (DATEADD(HOUR, 2 + :aantalUur , GETDATE()) )
	ORDER BY v.vertrektijd ASC";
    //DB loopt server kant 2 uur achter dus plus 2 voor biede
    $querry = $db->prepare($sql_Vluchtgegevens);
    $querry->bindParam(':aantalUur', $aantalUur, PDO::PARAM_INT);
    $querry->execute();
    $result = $querry->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
