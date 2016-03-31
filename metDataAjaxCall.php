<?php
ini_set('memory_limit', '-1');
echo "1";
//require_once('inc/config.php');
$connInfo = array(
    'user' => 'root',
    'pass' => 'ty1977',
    'db'   => 'metstation',
    'host' => 'localhost'
);
echo "2";
include('ssp.class.php');
echo "3";
$table = 'tblmhvmetdata';
$primaryKey = 'trnid';
$columns = array(
    array( 'db' => 'trnid', 'dt' => 0 ),
    array( 'db' => 'station_id',     'dt' => 1 ),
    array('db' => 'mdate',        'dt'        => 2),
	array('db' => 'celsius',        'dt'        => 3),
	array('db' => 'humidity',        'dt'        => 4),
	array('db' => 'dewpoint',        'dt'        => 5),
);
 

 

 
echo json_encode(
    SSP::simple( $_GET, $connInfo, $table, $primaryKey, $columns )
);
	

?>
