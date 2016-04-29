
<!-- called by formgetdistalt.php where specified distance and altitude difference are entered and submitted.  Find MH metstation and RGOB metstation pairs within specified distance and altitude difference.
 this is set up that works on XAMPP server with database there called rgob metdata which has tables
 tblstation (copied from metdata database on http://199.180.134.185/)
 and rgobstations-->

<style>
	tr:nth-child(even){
		background-color:#d3d3d3;
	}
	tr:nth-child(1){
		background-color:#808080;
	}
</style>

<?php

//Since connection type used is mysql_  hence mysqli_ is changed to the mysql_.

require_once( "inc/config.php" );
$dist = $_GET["dist"];			//values entered in form
$altdiff = $_GET["altdiff"];

/* $server = "127.0.0.1";   //this is XAMPP 
$dbuser = "root";
$password = "";

$link = mysqli_connect($server,$dbuser,$password);
mysqli_select_db($link,"rgob metdata");  */        

$sql = "SELECT * FROM tblstation";      //get the MH station information
$result = mysql_query($sql);
//$result = mysqli_query($link,$sql);

echo "<table>";
	echo "<tr><th>ID</th><th>    </th><th>Station</th><th>Altitude (m)</th><th>Distance apart (km)</th><th>Type</th>
	</tr>";


	while($row=mysql_fetch_array($result)){              //take each station and get lat, long and altitude
	$tblstation_id = $row["station_id"];
	$tblstation_name = $row["tshowog"];
	$lat1 = $row["latitude"];
	$long1 = $row["longitude"];
	$alt = $row["altitude"];

$sql = "SELECT * FROM rgobstations";      //get the RGOB station information
$result2 = mysql_query($sql);
//$result2 = mysqli_query($link,$sql);

while($row=mysql_fetch_array($result2)){

	$rgobstation_id = $row["station_id"];                //get lat, long and altitude
	$rgobstation = $row["station_name"]; 
	$type = $row["type"]; 				     //type of RGOB station (Class A, AWS, Class C)
	$lat2 = $row["latitude"];
   	$long2 = $row["longitude"];
	$alt2 = $row["altitude"];
   	

	$distance = distance($lat1, $long1, $lat2, $long2);             //function to calculate distance between pair of lat long co-ordinates (see below)
	
	if($distance < $dist AND ABS($alt-$alt2)<= $altdiff) {         //if distance is less than specified distance and altitude difference less than spec alt
		
		$distance = round($distance,2);				//print out the station pairs in a table

		echo "<tr>						
			<td>$tblstation_id
			<td>MH
			<td>$tblstation_name
			<td>$alt
		</tr>";

		echo "<tr>
			<td>$rgobstation_id
			<td> RGOB
			<td>$rgobstation
			<td>$alt2
			<td>$distance
			<td>$type
		</tr>";

        	}
	

	}

}
echo "</table>";

 
//Law of Cosines (suitable for short distances) to calculate distance between
//lat long co-ordinates (converted to radians) http://www.movable-type.co.uk/scripts/latlong.html

function distance($la1, $lon1, $la2, $lon2) {
  		$rad = M_PI / 180;
  		return acos(sin($la2*$rad) * sin($la1*$rad) + cos($la2*$rad) * cos($la1*$rad) * cos($lon2*$rad - $lon1*$rad)) * 6371;// Kilometers
	}

//mysqli_close($link);
?>
	
