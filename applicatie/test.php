<?php
$ww = 'unsafe-pass'; 
echo $ww; 
echo '<br>'; 
echo $hashWW = password_hash($ww , PASSWORD_DEFAULT);
echo '<br>';
if (password_verify($ww ,$hashWW)){
    echo 'Password is valid';
}