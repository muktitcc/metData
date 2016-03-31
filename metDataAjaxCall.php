<?php
error_reporting(E_ALL);
ini_set('memory_limit', '-1');
include_once __DIR__ .'/inc/config.php';
require('ssp.class.php');
var_dump($connInfo);
$table = 'tblmhvmetdata';
$primaryKey = 'trnid';
$columns = array(
    	array('db' => 	'trnid', 		'dt' => 0 ),
    	array('db' => 	'station_id',     	'dt' => 1 ),
    	array('db' => 	'mdate',        	'dt'        => 2),
	array('db' => 	'celsius',        	'dt'        => 3),
	array('db' => 	'humidity',        	'dt'        => 4),
	array('db' => 	'dewpoint',        	'dt'        => 5),
);
echo json_encode(SSP::simple( $_GET, $connInfo, $table, $primaryKey, $columns ));
?>
