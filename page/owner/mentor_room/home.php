<?php 
	session_start();
	if (!isset($_SESSION["ownerID"])) 
		exit("An error occured: user not defined. <a href='../../login.html'>Click here to login</a>");
	if (!isset($_GET["roomID"])) 
		exit("An error occured: page not defined.");

	$ownerID = $_SESSION["ownerID"];
	$_SESSION["roomID"] = $roomID = $_GET["roomID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mentor Room: home</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/mr_navbar.css">
	<link rel="stylesheet" href="../../../css/owner/mentor_room/home.css">
</head>
<body data-room-id="<?php echo $roomID ?>">
	<header>
		<nav>
			<div class="logo-wrapper"><p class="logo">LOGO</p><span class="mr-label">Mentor Room</span></div>
			<a href="#">Home</a>
			<a href="chat.php">Chat</a>
			<a href="resources.php">Resources</a>
			<button id="exit-btn">Exit</button>
		</nav>
	</header>
	<main>
		<section id="room-info">
			<h2 style="display:inline-block">Room Info</h2>
			<button id="close-btn">Close Mentor Room</button>
			<div id="ajax-created-date"></div>
			<div id="ajax-duration"></div>
			<h2>Business</h2>
			<div id="ajax-business-page"></div>
			<h2>Program</h2>
			<div id="ajax-program-page"></div>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/owner/mentor_room/home.js?v=<?php echo time(); ?>"></script>
</body>
</html>