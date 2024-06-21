<?php
require_once 'functies/db_connectie.php';

function genereerHTMLtabelVluchten($lijst) {
    $html = '<table>';
    $html.=     '<tr>   
                    <th>Vertrektijd</th>
                    <th>Luchthavencode</th>
                    <th>Vluchtnummer</th>
                    <th>Gate</th>
                    <th>Luchtvaartmaatschappij</th>
                </tr>';
    foreach ($lijst as $row) {
        $html.= '<tr>';
        $html.= '<td>'. implode('</td><td>', $row). '</td>';
        $html.= '</tr>';
    }
    $html.= '</table>';
    return $html;
}

function vluchtGegevensAankomendeAantalUur($aantal, $uurOfDag)
{
    $db = maakVerbinding();
    if($uurOfDag == 'uur'){
        $sql_Vluchtgegevens = 
        "SELECT  
                FORMAT (v.vertrektijd, 'HH:mm') as Vertrektijd,
                v.bestemming as Luchthavencode, 
                v.VLUCHTNUMMER AS Vluchtnummer, 
                gatecode as Gate, 
                m.naam as LuchvaartMaatschappij
        FROM Vlucht v
        LEFT JOIN Maatschappij m  ON v.maatschappijcode = m.maatschappijcode 
        WHERE vertrektijd 
                BETWEEN (DATEADD(HOUR, 2, GETDATE())) AND (DATEADD(HOUR, 2 + :aantal , GETDATE()) )
        ORDER BY v.vertrektijd ASC";
        //DB server kant loopt 2 uur achter dus plus 2 voor biede
    } else if($uurOfDag == 'dag'){
            $sql_Vluchtgegevens = 
        "SELECT  
            FORMAT(vertrektijd, 'dd-MMM-HH:mm') as Vertrektijd,
            v.bestemming as Luchthavencode, 
            v.VLUCHTNUMMER AS Vluchtnummer, 
            gatecode as Gate, 
            m.naam as LuchvaartMaatschappij 
        FROM Vlucht	v
        LEFT JOIN Maatschappij m ON v.maatschappijcode = m.maatschappijcode 
            WHERE vertrektijd BETWEEN GETDATE() AND DATEADD(DAY, :aantal, GETDATE())
        ORDER BY v.vertrektijd DESC";
    }
    $querry = $db->prepare($sql_Vluchtgegevens);
    $querry->bindParam(':aantal', $aantal, PDO::PARAM_INT);
    $querry->execute();
    $result = $querry->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}