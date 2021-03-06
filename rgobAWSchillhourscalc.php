<!-- calculate chill hours for rgobAWS metdata -->

<style>
	tr:nth-child(even){
		background-color:#d3d3d3;
	}
	tr:nth-child(1){
		background-color:#808080;
	}
</style>

<?php
require_once( "inc/config.php" );							// common connection variable is $conn
$link = mysqli_connect($connInfo["host"], $connInfo["user"], $connInfo["pass"]); 	// common db connection variable
mysqli_select_db($link,$connInfo["db"]);       						// common db connection variable   
          
$sql = "SELECT * FROM tblchillhourmodel";      //get the chill hour model information
$result = mysqli_query($link,$sql);
echo "<table>";
	echo "<tr><th>Station</th><th>Date1</th><th>Date2</th><th>Diff</th><th>Chilltime</th><th>Temp</th><th>Lower chillhrs</th><th>Middle chillhrs</th><th>Upper chillhrs</th><th>Count hrs</th>
	</tr>";
while($row=mysqli_fetch_array($result)){              //get values from model for lowerband, middleband and upperband chill temps
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
		//get the rgobAWS met stations Tmax for months Nov to Mar ordered by station and date - maybe should be avg of Tmax Tmin????
$sql = "SELECT * FROM tblrgobAWSmetdata WHERE month(bdate) NOT BETWEEN 4 AND 10 ORDER BY station_id, bdate";      
										//if there is a NULL ... need to address this
//$sql = "SELECT * FROM tblrgobAWSmetdata ORDER BY station_id, bdate"; 		//maybe should get all months in case chill season is adjusted?
$result2 = mysqli_query($link,$sql);
$rgobdate = 0; 				//date of record
$lowerch = 0;				//time in lower band over period
$middlech = 0;				//time in middle band over period
$upperch = 0;				//time in upper band over period
$counthrs = 0;				//total time over period to see if period is complete
$rgobstation_id = 0;			//station for record
$first = true;				//variable to indicate if very first record
while($row=mysqli_fetch_array($result2)){
	$newrgobdate = strtotime($row["bdate"]); 				//get next date 
	$difference = $newrgobdate-$rgobdate;					//get time difference between date and previous date
	$newstation = $row["station_id"];					//get the next station
	
	if($rgobstation_id <> $newstation or $difference > 3600){		//if change of station, or jump in time more than an hour
		$chilltime = 1;							//chilltime from previous time assumed 1 hour
	} else {				
		$chilltime = ($difference)/3600;				//otherwise get chilltime in hours - difference between rows
	}
	
	$newday = DATE('Y-m-d',$newrgobdate);					//get the date as just Y-m-d for inserting in chillhours table
	$date2 = DATE('Y-m-d H:i:s',$newrgobdate);				//get the date in format for printing out to monitor 
	$date1 = DATE('Y-m-d H:i:s',$rgobdate);					//get the previous date in format as above
	$oldday = DATE('Y-m-d',$rgobdate);					//get the previous date for comparison
	
	
	if(!$first){								//if not very first record
		if($rgobcelsius >= $temp1 and $rgobcelsius < $temp2){			//see if the temperature falls into lowerband
			$lowerch +=  ($bvaluelower*$chilltime);			//if it does, add time to lowerband chillhours
			}
		if($rgobcelsius >= $temp2 AND $rgobcelsius < $temp3){			//see if the temperature falls into middleband
			$middlech +=  ($bvaluemiddle*$chilltime);		//if it does, add time to middleband chillhours
			}
		if($rgobcelsius >= $temp3 AND $rgobcelsius <= $temp4){			//see if the temperature falls into upperband
			$upperch += ($bvalueupper*$chilltime);			//if it does, add time to upperband chillhours
			}		
	
		$counthrs += $chilltime;					//get the total hours for period over which data analysed to see if data complete
										//print out values
		echo "<tr>													
			<td>$rgobstation_id
			<td>$date1
			<td>$date2
			<td>$difference
                      	<td>$chilltime
			<td>$rgobcelsius
			<td>$lowerch
			<td>$middlech
			<td>$upperch
			<td>$counthrs
			
		</tr>";
				//if different station, or next day but same station, need to insert record and start adding up chill hours from zero
				//otherwise skip this, get next row and keep adding up chill hours
		if ($rgobstation_id <> $newstation or ($newday > $oldday and $rgobstation_id = $newstation)){		
														//print total hours for period to check
			echo "<tr>								
				<td>$counthrs				
			</tr>";
	 
			$sql_insert = "INSERT INTO tblrgobAWSchillhours (station_id, bdate, lowbandchillhrs, midbandchillhrs, upbandchillhrs, counthrs) VALUES ('$rgobstation_id', '$oldday', '$lowerch', '$middlech', '$upperch', '$counthrs')";
			mysqli_query($link,$sql_insert);			//insert the chillhours into chillhour table for that period
			$counthrs = 0;						//reset counthrs and chillhours to zero for next period
			$lowerch = 0;
			$middlech = 0;
			$upperch = 0;
        	}
	
	} else {								//if it was very first record, set $first to false and continue
  		$first = false;
	}
	$rgobstation_id = $row["station_id"];      				//set the current station to be the previous station          
	$rgobdate = strtotime($row["bdate"]); 					//set the current date to be the previous date
	$rgobcelsius = $row["tmax"];						//set temperature to previous row
}
//end of loop through data but have to deal with very last record
if($rgobcelsius >= $temp1 and $rgobcelsius < $temp2){			//see if the temperature falls into lowerband
	$lowerch +=  ($bvaluelower*$chilltime);				//if it does, add time to lowerband chillhours
	}
if($rgobcelsius >= $temp2 AND $rgobcelsius < $temp3){			//see if the temperature falls into middleband
	$middlech +=  ($bvaluemiddle*$chilltime);			//if it does, add time to middleband chillhours
	}
if($rgobcelsius >= $temp3 AND $rgobcelsius <= $temp4){			//see if the temperature falls into upperband
	$upperch += ($bvalueupper*$chilltime);				//if it does, add time to upperband chillhours
	}		
	
$counthrs += $chilltime;						//get the total hours for a day over which data analysed to see if data complete
echo "<tr>													
	<td>$rgobstation_id
	<td>$date1
	<td>$date2
	<td>$difference
        <td>$chilltime
	<td>$rgobcelsius
	<td>$lowerch
	<td>$middlech
	<td>$upperch
	<td>$counthrs
			
</tr>";
$sql_insert = "INSERT INTO tblrgobAWSchillhours (station_id, bdate, lowbandchillhrs, midbandchillhrs, upbandchillhrs, counthrs) VALUES ('$rgobstation_id', '$oldday', '$lowerch', '$middlech', '$upperch', '$counthrs')";
mysqli_query($link,$sql_insert);                            // insert data after last row
echo "</table>";
mysqli_close($link);
?>
	
