<?php
    $aantaldagen = "";

    if(ISSET($_GET['dagEen'], $_GET['dagTwee'])){
    $dagEen = $_GET['dagEen'];
    $dagTwee = $_GET['dagTwee'];

    $start = date_create($dagEen);
    $doel = date_create($dagTwee);

    $dagen = date_diff($start, $doel) ->format ('%a');

    $aantaldagen = 'Het verschil is '. $dagen . ' dagen';    
       
    }
    else{
        $aantaldagen = 'Vul twee dagen in';
    }
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dagen tot Sinterklaas</title>
</head>
<body>
    <form methode="GET" action= "hoelangnog.php">
        <input type="text" name="dagEen">
        <input type="text" name="dagTwee">
        <input type="submit" name="submit" value= "bereken">
    </from>
    <br/>
    <?= $aantaldagen ?>
</body>
</html>