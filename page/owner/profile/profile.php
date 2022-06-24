<?php 
	session_start();
	if (isset($_SESSION["pageID"]))
		unset($_SESSION["pageID"]);
	if (!isset($_SESSION["ownerID"])) 
		exit("An error occured: user not defined. <a href='../../login.html'>Click here to login</a>");

	$ownerID = $_SESSION["ownerID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/profile/profile.css?v=<?php echo time(); ?>">
</head>
<body>
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="../search/searchasowner.php">Find A Mentor</a>
				<a href="create_page.php">Create Business Page</a>
			</div>
			<div class="right-column">
				<a href="#"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<main>
		<section id="notification">
			<div id="ajax-notif"></div>
		</section>
		<section id="owner-info">
			<span class="user-type">Business owner</span>
			<div class="info-wrapper">
				<p id="ajax-owner-name" data-owner-id="<?php echo $ownerID ?>">?</p>
				<a class="edit-profile" href="edit_profile.php">Edit Profile</a>
			</div>
		</section>
		<br>
		<section id="dashboard">
			<nav class="dashboard-nav">
				<div class="dashboard-wrapper">
					<a id="business-page-link" class="selected-link" href="#">Business Page</a>
					<a id="interest-request-link" href="#">Interest Request</a>
					<a id="mentor-room-link" href="#">Mentor Room</a>
				</div>
			</nav>
			<div id="ajax-container">?</div>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/owner/profile/profile.js?v=<?php echo time(); ?>"></script>
</body>
</html>