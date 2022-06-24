<?php 
	session_start();
	if (!isset($_GET["pageID"]))
		exit("An error occured: page not defined");

	$_SESSION["pageID"] = $pageID = (int) $_GET["pageID"];
	$pageTitle = $_GET["pageTitle"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $pageTitle ?></title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/profile/business_page.css">
</head>
<body data-page-id="<?php echo $pageID ?>">
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
	<main>
		<section id="notification">
			<div id="ajax-notif" style="display:none"></div>
		</section>
		<section id="business-info">
			<div id="ajax-business-info">?</div>
			<a class="edit-page-link" href="edit_page.php">Edit Page</a>
			<button id="delete-btn">Delete Page</button>
		</section>

		<section id="business-problem">
			<h2>Business Problem</h2>
			<div id="ajax-business-problem">?</div>
		</section>

		<section id="business-desc">
			<h2>About</h2>
			<div id="ajax-business-desc">?</div>
			<h2>Business Problem Proposal</h2>
			<div id="ajax-business-proposal"></div>
		</section>

		<section id="mentoring-offer">
			<h2>Mentoring Offer</h2>
			<div id="ajax-mentoring-offer"></div>
		</section>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/owner/profile/business_page.js?v=<?php echo time(); ?>"></script>
</body>
</html>