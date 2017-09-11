<?php
$file = "/www/.htaccess";
copy(__DIR__."".$file."_temp", __DIR__."".$file."");
unlink(__DIR__."".$file."_temp");

$file = "/app/bootstrap.php";
copy(__DIR__."".$file."_temp", __DIR__."".$file."");
unlink(__DIR__."".$file."_temp");

?>

