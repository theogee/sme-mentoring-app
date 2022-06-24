<?php 
	session_start();
	if (!isset($_SESSION["expertID"]))
		exit("An error occured: user not defined");
	if (!isset($_SESSION["roomID"]))
		exit("An error occured: page not defined");

	$expertID = $_SESSION["expertID"];
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
	<link rel="stylesheet" href="../../../css/expert/mentor_room/add_resources.css">
</head>
<body data-room-id="<?php echo $roomID ?>" data-poster-id="<?php echo $expertID ?>">
	<header>
		<nav>
			<div class="logo-wrapper"><p class="logo">LOGO</p><span class="mr-label">Mentor Room</span></div>
			<a href="home.php?<?php echo 'roomID='.$roomID ?>">Home</a>
			<a href="chat.php">Chat</a>
			<a href="resources.php">Resources</a>
			<button id="exit-btn">Exit</button>
		</nav>
	</header>
	<main>
		<section id="add-resource">
			<h1>Add Resources</h1>
			<label for="title">Resource title: </label>
			<input type="text" id="title">
			<br><br>
			<label for="msg">Message: </label>
			<br>
			<span class="textarea" role="textbox" contenteditable id="msg"></span>
			<br><br>
			<label for="file">Upload file:</label>
			<button id="choose-file-btn">browse</button>
			<button id="remove-file-btn" style="display:none">Remove File</button>
			<span id="file-name">No file chosen</span>
			<input type="file" id="file" style="display:none">
			<br><br>
			<button id="post-btn">post</button>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/expert/mentor_room/add_resources.js?v=<?php echo time(); ?>"></script>
</body>
</html>