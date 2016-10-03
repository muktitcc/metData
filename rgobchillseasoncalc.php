
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
	echo "<tr><th>Station</th><th>Season</th><th>Lower chillhrs</th><th>Middle chillhrs</th><th>Upper chillhrs</th><th>Count</th>
	</tr>";


$sql = "SELECT CH.station_id AS station, S.season AS season, SUM(CH.lowbandchillhrs), SUM(CH.midbandchillhrs), SUM(CH.upbandchillhrs), COUNT(*)       
 FROM tblrgobchillhours  CH
 INNER JOIN tblchillseasons S
         ON CH.bdate >= S.startDate 
        and CH.bdate <=  S.endDate
 GROUP BY CH.station_id, S.season";      //get the RGOB chillhours
											
$result2 = mysqli_query($link,$sql);

while($row=mysqli_fetch_array($result2)){

	$rgobstation_id = $row["station"];                //
	$rgobdate = $row["season"]; 
	$lowerch = $row["SUM(CH.lowbandchillhrs)"];
	$middlech = $row["SUM(CH.midbandchillhrs)"];
	$upperch = $row["SUM(CH.upbandchillhrs)"];
	$numb = $row["COUNT(*)"];
 
	
		echo "<tr>						
			<td>$rgobstation_id
			<td>$rgobdate
			<td>$lowerch
			<td>$middlech
			<td>$upperch
			<td>$numb
									
		</tr>";

}
echo "</table>";


mysqli_close($link);
?>
	
