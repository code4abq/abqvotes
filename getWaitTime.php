<?php
// Program to retrieve rows from the WaitTime table, where people reported wait times at a voting location
// program call: getWaitTime.php?loc=nn -- where "nn" is the location ID of the voting location
// output is JSON [persons, booths & timestamp] or in case of error, [status & message]

$servername = "mysql.code4abq.org";
$username = "code4abqorg1";
$password = "gjk%795h4#46";
$dbname = "votinglocation";

$starttime = new DateTime();
// set for how far back you want to retrieve data
$starttime->modify("-60 minutes");
$persons = 0;
$booths = 0;
$timestamp = '';
$approvedpersons = 0;
$approvedbooths = 0;
$approvedtimestamp = '';
$sumpersons = 0;
$sumbooths = 0;
$countpersons = 0;
$countbooths = 0;
$rowcount = 0;

//// input field validation
//$loc = test_input($_GET["loc"]);
//if ($loc > "0" and $loc < "100") {
//} else {
//	$loc = 0;
//}


//if ($loc > 0) {
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		$status = array('status' => 'error', 'message' => 'Connection failed:  $conn->connect_error');
	} else {
		$status=array();

//		$sql = "SELECT PersonCount, BoothCount, IsApprovedUser, CreatedTimestamp FROM WaitTime WHERE LocationId = $loc AND CreatedTimestamp >= $starttime ORDER BY CreatedTimestamp DESC";
//		$sql = "SELECT LocationId, PersonCount, BoothCount, IsApprovedUser, CreatedTimestamp FROM WaitTime WHERE LocationId = $loc ORDER BY CreatedTimestamp DESC";
//		$sql = "SELECT LocationId, PersonCount, BoothCount, IsApprovedUser, CreatedTimestamp FROM WaitTime ORDER BY LocationId ASC";
//		$sql = "SELECT LocationId, PersonCount, CreatedTimestamp FROM WaitTime ORDER BY LocationId ASC";

//		$sql = "SELECT LocationId, PersonCount, CreatedTimestamp FROM WaitTime
//  					/* Subquery returns id and dateadded grouped by id */
//					  JOIN (
//						  SELECT LocationId, MAX(CreatedTimestamp) AS CreatedTimestamp FROM WaitTime GROUP BY LocationId
//						 /* JOIN condition is on both id and dateadded between the two tables */
//					  ) maxtimestamp ON WaitTime.LocationId = maxtimestamp.LocationId AND WaitTime.CreatedTimestamp = maxtimestamp.CreatedTimestamp";

		$sql = "SELECT WaitTime.LocationId, PersonCount, WaitTime.CreatedTimestamp FROM WaitTime JOIN (
							SELECT LocationId, MAX(CreatedTimestamp) AS CreatedTimestamp FROM WaitTime GROUP BY LocationId
					  ) maxtimestamp ON
					  		WaitTime.LocationId = maxtimestamp.LocationId AND
					  		WaitTime.CreatedTimestamp = maxtimestamp.CreatedTimestamp";

//		$sql = "SELECT  audittable.id,  name,  shares,  audittable.dateadded FROM  audittable
//					/* Subquery returns id dateadded grouped by id */
//  						JOIN (
//  							SELECT id, MAX(dateadded) AS dateadded FROM audittable GROUP BY id
//						 /* JOIN condition is on both id and dateadded between the two tables */
//						) maxtimestamp ON audittable.id = maxtimestamp.id AND audittable.dateadded = maxtimestamp.dateadded";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()){
				array_push($status,$row);
			}
		}
		$conn->close();
	}

//} else {
//	$status = array('status' => 'error', 'message' => 'invalid location');
//}

// send result as JSON sstring
echo json_encode($status);


// trim and strip bad data from input field
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


/*
		// analyze data of each row
		while($row = $result->fetch_assoc()) {
			$rowcount = $rowcount + 1;

			// set variables from first row (latest) record
			if ($rowcount == 1) {
				$persons =  $row["PersonCount"];
				$booths = $row["BoothCount"];
				$timestamp = $row["CreatedTimestamp"];
			}

			// if from an approved user, use those values and break out of loop
			if ($row["IsApprovedUser"] == 1) {
				$approvedpersons =  $row["PersonCount"];
				$approvedbooths = $row["BoothCount"];
				$approvedtimestamp = $row["CreatedTimestamp"];
				break;
			}

			// sum number of persons and add to count
			$sumpersons = $sumpersons + $row["PersonCount"];
			$countpersons = $countpersons + 1;

			if ($row["BoothCount"] > 0) {
				$sumbooths = $sumbooths + $row["BoothCount"];
				$countbooths = $countbooths + 1;
			}
		}

		// use approved user record, if it exists
		if ($approvedpersons > 0 || $approvedbooths > 0) {
			$persons =  $approvedpersons;
			$booths = $approvedbooths;
			$timestamp = $approvedtimestamp;
		} else {
			// get average row and booth counts
			if($countpersons > 0) {
				$avgpersons = $sumpersons / $countpersons;
			} else {
				$avgpersons = 0;
			}

			if($countbooths > 0) {
				$avgbooths = $sumbooths / $countbooths;
			} else {
				$avgbooths = 0;
			}

			// check if last entry is reasonable (no more than 50% above the average), if not, use average
			if ($persons > ($avgpersons * 1.5) or $booths > ($avgbooths * 1.5)) {
				$persons =  $avgpersons;
				$booths = $avgbooths;
				$timestamp = $avgtimestamp;
			}
		}

	}
	$status = array('persons' => $persons, 'booths' => $booths, 'timestamp' => $timestamp);

	*/

?>
