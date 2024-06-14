<?php
    $datumNu = getdate();
    $dagVanDeWeek = $datumNu['weekday'];

    if($dagVanDeWeek == 'Saturday' || $dagVanDeWeek == 'Sunday'){
        $antwoord = "HET IS WEEKEND " . $dagVanDeWeek;
    } else {
        $antwoord = "Het is geen weekend" . $dagVanDeWeek;
    }
    
    define('HEADER', 'test');
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Is het al weekend?</title>
    <?= $antwoord ?>
    <h1>HEADER <h2>
</head>
<body>

</body>
</html>