<?php 
	session_start();
	if (!isset($_SESSION["expertID"]))
		exit("An error occured: user not defined");
	if (!isset($_SESSION["roomID"]))
		exit("An error occured: page not defined");

	$roomID = $_SESSION["roomID"];
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mentor Room: resources</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/mr_navbar.css">
	<link rel="stylesheet" href="../../../css/expert/mentor_room/resources.css">
</head>
<body data-room-id="<?php echo $roomID ?>">
	<header>
		<nav>
			<div class="logo-wrapper"><p class="logo">LOGO</p><span class="mr-label">Mentor Room</span></div>
			<a href="home.php?<?php echo 'roomID='.$roomID ?>">Home</a>
			<a href="chat.php">Chat</a>
			<a href="#">Resources</a>
			<button id="exit-btn">Exit</button>
		</nav>
	</header>
	<main>
		<section id="resources">
			<h2>Resources</h2>
			<p id="ajax-available-space"></p>
			<button id="add-btn">Add Resource</button>
			<div id="ajax-resources"></div>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/expert/mentor_room/resources.js?v=<?php echo time(); ?>"></script>
</body>
</html>