<!-- calculate chill hours by season for rgob metdata -->
<!-- this program not required as can be done by simple SQL query -->
<style>
	tr:nth-child(even){
		background-color:#d3d3d3;
	}
	tr:nth-child(1){
		background-color:#808080;
	}
</style>

<?php

require_once( "inc/config.php" );// common connection variable is $conn

$link = mysqli_connect($connInfo["host"], $connInfo["user"], $connInfo["pass"]); // common db connection variable
mysqli_select_db($link,$connInfo["db"]);       // common db connection variable   
     


echo "<table>";
	echo "<tr><th>Station</th><th>Season</th><th>Lower chillhrs</th><th>Middle chillhrs</th><th>Upper chillhrs</th><th>Counthrs</th>
	</tr>";


$sql = "SELECT CH.station_id AS station, S.season AS season, SUM(CH.lowbandchillhrs), SUM(CH.midbandchillhrs), SUM(CH.upbandchillhrs), SUM(CH.counthrs)       
 FROM tblmhvchillhours  CH
 INNER JOIN tblchillseasons S
         ON CH.mdate >= S.startDate 
        and CH.mdate <=  S.endDate
 GROUP BY CH.station_id, S.season";      //get the MHV chillhours
											
$result2 = mysqli_query($link,$sql);

while($row=mysqli_fetch_array($result2)){

	$rgobstation_id = $row["station"];                //
	$rgobdate = $row["season"]; 
	$lowerch = $row["SUM(CH.lowbandchillhrs)"];
	$middlech = $row["SUM(CH.midbandchillhrs)"];
	$upperch = $row["SUM(CH.upbandchillhrs)"];
	$counthrs = $row["SUM(CH.counthrs)"];
 
	
		echo "<tr>						
			<td>$rgobstation_id
			<td>$rgobdate
			<td>$lowerch
			<td>$middlech
			<td>$upperch
			<td>$counthrs
									
		</tr>";

}
echo "</table>";


mysqli_close($link);
?>
	
