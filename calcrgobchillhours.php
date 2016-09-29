<!-- calculate chill hours for rgob metdata -->

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
	echo "<tr><th>ID</th><th>Date</th><th>Tmax</th><th>Tmin</th><th>Tan theta</th><th>Lower chillhrs</th><th>Middle chillhrs</th><th>Upper chillhrs</th><th>Total chillhrs</th>
	</tr>";


	while($row=mysqli_fetch_array($result)){              //get values from model
	$band = $row["band"];
	switch($band){
		case "Lower";
			$temp1 = $row["minband"]; 		//temp range of lower band 
	 		$temp2 = $row["maxband"];
			$bvaluelower = $row["bvalue"];		//variable for band
			break;

		case "Middle";
			$temp3 = $row["maxband"];		//upper temp for mid band (use upper temp of lower band for lower temp of range)
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


$sql = "SELECT * FROM tblrgobAmetdata where tmax IS NOT NULL and tmin IS NOT NULL and (month(bdate) NOT BETWEEN 4 AND 10)";      //get the RGOB met stations Tmax and Tmin for months Nov to Mar
											//if there is a NULL tmax or tmin row will not be selected ... need to address this
$result2 = mysqli_query($link,$sql);

while($row=mysqli_fetch_array($result2)){

	$rgobstation_id = $row["station_id"];                //
	$rgobdate = $row["bdate"]; 
	$tmax =  $row["tmax"]; 				     //
	$tmin =  $row["tmin"];

if($tmax == $tmin){  					//deal with tmax and tmin being equal not to divide by zero
	$tmax+=0.1;
}

	$tantheta = 12/($tmax-$tmin);  	

	$twod = calc($tantheta, $temp2, $tmin);             //function to calculate - lower band
	$f = calc($tantheta, $temp1, $tmin);
	$lowerch = ($twod-$f)*$bvaluelower;

	$twod = calc($tantheta, $temp3, $tmin);             //function to calculate - middle band
	$f = calc($tantheta, $temp2, $tmin);
	$middlech = ($twod-$f)*$bvaluemiddle;

	$twod = calc($tantheta, $temp4, $tmin);             //function to calculate for upper band
	$f = calc($tantheta, $temp3, $tmin);
	$upperch = ($twod-$f)*$bvalueupper;

$total = ($lowerch+$middlech+$upperch);

$sql_insert = "INSERT INTO tblrgobchillhours (station_id, bdate, lowbandchillhrs, midbandchillhrs, upbandchillhrs) VALUES ('$rgobstation_id', '$rgobdate', '$lowerch', '$middlech', '$upperch')";
mysqli_query($link,$sql_insert);
	
		echo "<tr>						
			<td>$rgobstation_id
			<td>$rgobdate
			<td>$tmax
			<td>$tmin
			<td>$tantheta
			<td>$lowerch
			<td>$middlech
			<td>$upperch
			<td>$total
			
		</tr>";

}
echo "</table>";

 
//calculation
//

function calc($tanth, $temp, $tmi) {
  		if ($tanth * ($temp-$tmi) > 12){
  		return 24;
		}

		if ($temp-$tmi < 0){
		return 0;
		}

		else {
		return (2*$tanth*($temp-$tmi));
		}
	}

mysqli_close($link);
?>
	
