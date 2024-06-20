<?php
define("FOOTER", maakFooter());

function maakFooter(){
    $header = <<<FOOTER
    <footer>
        <section>
            <h4><a href="Privacyverklaring.php">privacyverklaring</a></h3>
        </section>
        <section>
            <h4>©Gerle Airport </h3>
        </section>
    </footer>
    FOOTER;

return $header;
}
?>