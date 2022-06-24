<?php 
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$expertID = $_SESSION["expertID"];

	require_once "../../../../utility/dbconn.php";
	$dbconn = connect();
	//Post to take data.
	$NA = $_POST['name'];
	$EM = $_POST['email'];
	$PWD = $_POST['password'];
	$CO = $_POST['company'];
	$PR = $_POST['profession'];
	$cityID = (int) $_POST['city-id'];


	$query3 = "UPDATE expert SET name = '$NA', city_id = $cityID, email = '$EM', password = '$PWD', company='$CO',profession='$PR' WHERE id = $expertID"; 

	$result3 = $dbconn->query($query3);
	if($result3)
		header("location: ../profile.php");
	else
		echo "Error: ". $query3 . "<br>" . $dbconn->error ;
}
?>