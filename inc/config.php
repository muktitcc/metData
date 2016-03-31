<?php
$connInfo = array(
    'user' => 'root',
    'pass' => 'ty1977',
    'db'   => 'metstation',
    'host' => 'localhost'
);
$conn = mysql_connect($connInfo["host"], $connInfo["user"], $connInfo["pass"]) or die ("Error connecting to database");
mysql_select_db($connInfo["db"],$conn);

?>
