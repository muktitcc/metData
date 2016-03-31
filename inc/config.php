<?php
$connInfo = array(
    'user' => 'adminCloneTest',
    'pass' => '',
    'db'   => '',
    'host' => ''
);
$conn = mysql_connect($connInfo['host'], $connInfo['user'], $connInfo['pass']) or die ("Error connecting to database");
mysql_select_db($connInfo['db'],$conn);

?>
