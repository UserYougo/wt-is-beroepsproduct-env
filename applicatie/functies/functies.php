<?php
require_once 'PHP_defaultIncludes/db_connectie.php';

function maakHTMLpassegiertabel($lijst)
{
    $tabelPassegier = '<table>';
    $tabelPassegier .=     '<tr>   
                <th>Vertrektijd</th>
                <th>Luchthavencode</th>
                <th>Vluchtnummer</th>
                <th>Gate</th>
                <th>Luchtvaartmaatschappij</th>
            </tr>';
    foreach ($lijst as $row) {
        $tabelPassegier .= '<tr>';
        $tabelPassegier .= '<td>' . implode('</td><td>', $row) . '</td>';
        $tabelPassegier .= '</tr>';
    }
    $tabelPassegier .= '</table>';
    return $tabelPassegier;
}
