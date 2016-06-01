
<!-- calculate chill hours for mhv metdata -->

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

      

$sql = "SELECT * FROM tblchillhourmodel";      //get the chill hour model information
$result = mysqli_query($link,$sql);

echo "<table>";
	echo "<tr><th>ID</th><th>Date</th><th>Lower chillhrs</th><th>Middle chillhrs</th><th>Upper chillhrs</th><th>Total chillhrs</th>
	</tr>";


	while($row=mysqli_fetch_array($result)){              //get values from model
	$band = $row["band"];
	switch($band){
		case "Lower";
			$temp1 = $row["minband"]; 		
	 		$temp2 = $row["maxband"];
			$bvaluelower = $row["bvalue"];
			break;

		case "Middle";
			$temp3 = $row["maxband"];
			$bvaluemiddle = $row["bvalue"];
			break;

		case "Upper";
			$temp4 = $row["maxband"];
			$bvalueupper = $row["bvalue"];
			break;

		default;
			echo "band that isn't Lower, Middle or Upper";
	}

}


$sql = "SELECT * FROM tblmhvmetdata WHERE month(mdate) NOT BETWEEN 4 AND 10 ORDER BY station_id, mdate";      //get the MHV met stations celsius for months Nov to Mar
											//if there is a NULL ... need to address this
$result2 = mysqli_query($link,$sql);

//$lowerch = 0;
//$middlech = 0;
//$upperch = 0;
//$count = 0;

while($row=mysqli_fetch_array($result2)){

	$newmhvdate = $row["mdate"]; 		//get date of next record.  If it is new day, insert values for previous day and reset totals
	$mhvdat = strtotime($newmhvdate);
	$newmhvdate = DATE('Y-m-d',$mhvdat);
	
	IF($newmhvdate > $mhvdate){
		//switch($count){
			//case 24:			//if not 24hr record for day, data exluded. Order?  Need to address if month/season incomplete
			$sql_insert = "INSERT INTO tblmhvchillhours (station_id, mdate, lowbandchillhrs, midbandchillhrs, upbandchillhrs) VALUES ('$mhvstation_id', '$mhvdate', '$lowerch', '$middlech', '$upperch')";
			mysqli_query($link,$sql_insert);
		//}
	
		$count = 0;		//reset totals
		$lowerch = 0;
		$middlech = 0;
		$upperch = 0;
	
	}

	$mhvstation_id = $row["station_id"];                //start with next record
	$mhvdate = $row["mdate"]; 
	$mhvdat = strtotime($mhvdate);
	$mhvdate = DATE('Y-m-d',$mhvdat);
	$mhvcelsius =  $row["celsius"]; 

	IF($mhvcelsius >= $temp1 and $mhvcelsius < $temp2){            //if temp falls into lowerband >=lower temp but <upper temp
			$lowerch = $lowerch + $bvaluelower;
			}

	IF ($mhvcelsius >= $temp2 AND $mhvcelsius < $temp3){		//middle band
			$middlech = $middlech + $bvaluemiddle;
			}

	IF ($mhvcelsius >= $temp3 AND $mhvcelsius <= $temp4){		//upper band
			$upperch += $bvalueupper;
			}

$count++;								//counting number of record in a day to make sure 24

$total = ($lowerch+$middlech+$upperch);					//not needed

		echo "<tr>						
			<td>$mhvstation_id
			<td>$mhvdate
			<td>$lowerch
			<td>$middlech
			<td>$upperch
			<td>$total
			<td>$count
			
		</tr>";


}
echo "</table>";

mysqli_close($link);
?>
	
