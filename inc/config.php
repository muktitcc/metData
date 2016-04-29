<?php
$connInfo = array(
    'user' => 'metUser',
    'pass' => 'none',
    'db'   => 'metstation',
    'host' => 'localhost'
);
$conn = mysql_connect($connInfo["host"], $connInfo["user"], $connInfo["pass"]) or die ("Error connecting to database");
$link = mysql_connect($connInfo["host"], $connInfo["user"], $connInfo["pass"]) or die ("Error connecting to database"); // Janny's connection variable
mysql_select_db($connInfo["db"],$conn);
mysql_select_db($connInfo["db"],$link); // for Janny's connection variable $link

?>
