<?php
$connInfo = array(
    'user' => 'metUser1',
    'pass' => 'none',
    'db'   => 'metstation',
    'host' => 'localhost'
);
$conn = mysql_connect($connInfo["host"], $connInfo["user"], $connInfo["pass"]) or die ("Error connecting to database");
mysql_select_db($connInfo["db"],$conn);

?>
