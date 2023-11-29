<?php
$vlucht = $_GET['vlucht'];
$vluchtnummer = $_GET['vluchtnummer'];
$vluchthaven = $_GET['vluchthaven'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>PHP voorbeeld</title>
</head>
<body>

    Vliegtuig: <?php echo $vlucht ?>    met vluchtnummer: <?php echo $vluchtnummer ?>    landt op: <?php echo $vluchthaven ?>.<br>

</body>
</html>