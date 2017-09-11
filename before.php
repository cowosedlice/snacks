<?php
######################################################
$file = "/www/.htaccess";
    //uděláme zálohu původního souboru
    copy(__DIR__."".$file."",__DIR__."".$file."_temp");

    $content = file_get_contents(__DIR__."$file");
    $search='#RewriteBase /';
    $replace='RewriteBase /';

    $content = str_replace("$search","$replace", $content);
    
    // Save the file
    file_put_contents(__DIR__."$file", "$content");
######################################################
$file = "/app/bootstrap.php";
    //uděláme zálohu původního souboru
    copy(__DIR__."".$file."",__DIR__."".$file."_temp");
    
    $content = file_get_contents(__DIR__."$file");
    $search='$configurator->setDebugMode';
    $replace='//$configurator->setDebugMode';

    $content = str_replace("$search","$replace", $content); 

        $search2='$configurator->addConfig(__DIR__ . \'/config/config.local.neon\');';
        $replace2='//$configurator->addConfig(__DIR__ . \'/config/config.local.neon\');';

        $content = str_replace("$search2","$replace2", $content);
    // Save the file
    file_put_contents(__DIR__."$file", "$content");
 
?>

