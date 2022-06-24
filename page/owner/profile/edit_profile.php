<?php 
	session_start();
	if (!isset($_SESSION["ownerID"]))
		exit("An error occured: user not defined");

	$ownerID = $_SESSION["ownerID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Profile</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/profile/edit_profile.css">
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
				<a href="profile.php"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<br>
	<main>
		<form>
			<input type="hidden" id="owner-id" value="<?php echo $ownerID ?>">

			<h1>Edit Profile</h1>

			<div class="owner-name">
				<label for="ajax-owner-name">Name:</label>
				<input type="text" id="ajax-owner-name">
			</div>

			<div class="owner-email">
				<label for="ajax-owner-email">Email:</label>
				<input type="email" id="ajax-owner-email">
			</div>

			<div class="owner-password">
				<label for="ajax-owner-password">Password:<button id="show-pwd">show</button></label>
				<input type="password" id="ajax-owner-password">
			</div>

			<div class="owner-province">
				<label for="ajax-province">Province:</label>
				<select id="ajax-province"></select>
			</div>

			<div class="owner-city">
				<label for="ajax-city">City:</label>
				<select id="ajax-city"></select>
			</div>
			
			<input type="submit" id="update-btn" value="Update">
		</form>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/owner/profile/edit_profile.js?v=<?php echo time(); ?>"></script>
</body>
</html>