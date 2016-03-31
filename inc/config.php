<?php
$connInfo = array(
<<<<<<< HEAD
    'user' => 'root',
    'pass' => 'ty1977',
    'db'   => 'metstation',
    'host' => 'localhost'
=======
    'user' => '',
    'pass' => '',
    'db'   => '',
    'host' => ''
>>>>>>> origin/master
);
$conn = mysql_connect($connInfo['host'], $connInfo['user'], $connInfo['pass']) or die ("Error connecting to database");
mysql_select_db($connInfo['db'],$conn);

?>
