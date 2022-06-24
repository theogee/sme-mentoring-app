<?php 
	session_start();
	if (!isset($_SESSION["ownerID"])) 
		exit("An error occured: user not defined. <a href='../../login.html'>Click here to login</a>");
	if (!isset($_SESSION["roomID"])) 
		exit("An error occured: page not defined.");
 
 	$roomID = $_SESSION["roomID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Close Mentor Room</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/owner/mentor_room/close_confirmation.css">
</head>
<body data-room-id="<?php echo $roomID ?>">
	<main>
		<section id="room-info">
			<h2>Room Info</h2>
			<div id="ajax-created-date"></div>
			<div id="ajax-duration"></div>
			<h2>Business</h2>
			<div id="ajax-business-page"></div>
			<h2>Program</h2>
			<div id="ajax-program-page"></div>
		</section>
		<p class="confirmation-text">You can't recover this mentor room once you close it. Do you want to procede?</p>
		<button id="close-yes">yes</button>
		<button id="close-no">no, go back</button>
		<br>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/owner/mentor_room/close_confirmation.js?v=<?php echo time(); ?>"></script>
</body>
</html>