<?php
require_once( "inc/config.php" );
ini_set('memory_limit', '-1');
$out = '';
$filename_prefix = 'metData';  // Set filename
$csv_hdr = "Trn#,Station,Date time,Celsius,Humidity,Dewpoint";
if (isset($csv_hdr)) {
$out .= $csv_hdr;
$out .= "\n";
}
$result=mysql_query("select * from tblmhvmetdata");
    if (mysql_num_rows($result) > 0) {
         while ($row = mysql_fetch_assoc($result)) {
            $csv_output .= $row['trnid'] . ", ".$row['station_id'] . ", ". $row['mdate'] . ", ". $row['celsius'] . ", ".$row['humidity'] . ", ".$row['dewpoint'] . "\n"; 
         } 
    } 
if (isset($csv_output)) {
$out .= $csv_output;
}
$filename = $filename_prefix."_".date("Y-m-d_H-i",time());
header("Content-type: application/vnd.ms-excel");
header("Content-Encoding: UTF-8");
header("Content-type: text/csv; charset=UTF-8");
header("Content-disposition: metData" . date("Y-m-d") . ".xls");
header("Content-disposition: filename=".$filename.".xls");
echo "\xEF\xBB\xBF"; // UTF-8 BOM
print $out;
exit;
?>
