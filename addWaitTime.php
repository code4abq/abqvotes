<?php
// Program to insert a row into the WaitTime table, when people are reporting wait times at a voting location
// program call: addWaitTime.php?loc=nn&persons=nn&booth=nn&msg=xxxx -- where "nn" and "xxxx" are values passed
// output is JSON [status & message]

$servername = "mysql.code4abq.org";
$username = "code4abqorg1";
$password = "gjk%795h4#46";
$dbname = "votinglocation";

// list of approved user names (add more as needed)
$approved = array("vote1", "vote2", "vote3");
$arrlength = count($approved);

// input field validation
$loc = test_input($_GET["loc"]);
if ($loc > "0" and $loc < "100") {
} else {
	$loc = 0;
}

$persons = test_input($_GET["persons"]);
if ($persons > "0" and $persons < "1000") {
} else {
	$persons = 0;
}

$booth = test_input($_GET["booth"]);
if ($booth > "0" and $booth < "100") {
} else {
	$booth = 0;
}

$approveduser = 0;
for($x = 0; $x < $arrlength; $x++) {
	if ($_GET["msg"] == $approved[$x]) {
		$approveduser = 1;
	}
}

if ($loc > 0 and $persons > 0) {
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		$status = array('status' => 'error', 'message' => 'Connection failed:  $conn->connect_error');
	} else {

		$sql = "INSERT INTO WaitTime (LocationId, PersonCount, BoothCount, IsApprovedUser)
		VALUES ($loc, $persons, $booth, $approveduser)";

		if ($conn->query($sql) === TRUE) {
			$status = array('status' => 'success', 'message' => 'New record created successfully');
		} else {
			$status = array('status' => 'error', 'message' => '$sql . $conn->error');
		}

		$conn->close();
	}

} else {
	$status = array('status' => 'error', 'message' => 'invalid data');
}

// send result as JSON sstring
echo json_encode($status);


// trim and strip bad data from input field
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

?>
