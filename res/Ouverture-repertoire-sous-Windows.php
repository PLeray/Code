<?php

execInBackground('explorer /select,"..\..\TIRAGES\2020-10-26-L2-Lycee Aime Cesaire-CLISSON"');

//$URL = $_SERVER['HTTP_REFERER'];
//echo $URL;
header('Location: history.go(-1)' );

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        exec($cmd . " > /dev/null &");  
    }
}
?>