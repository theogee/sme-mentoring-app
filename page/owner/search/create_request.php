<?php 
	session_start();
	if (!isset($_SESSION["ownerID"]))
		exit("An error occured: user not defined");
	if (!isset($_SESSION["programPageID"]) && !isset($_GET["businessPageID"]))
		exit("An error occured: page not defined");

	$businessPageID = $_GET["businessPageID"];
	$programPageID = $_SESSION["programPageID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Create interest request</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/search/create_request.css">
</head>
<body data-business-page-id="<?php echo $businessPageID; ?>" data-program-page-id="<?php echo $programPageID; ?>">
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="searchasowner.php">Find A Mentor</a>
				<a href="../profile/create_page.php">Create Business Page</a>
			</div>
			<div class="right-column">
				<a href="../profile/profile.php"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<main>
		<section id="request-info">
			<div id="ajax-request-header"></div>
			<h2>From:</h2>
			<div id="ajax-business-page"></div>
			<h2>To:</h2>
			<div id="ajax-program-page"></div>
			<br>
			<button id="send-btn">Send request</button>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/owner/search/create_request.js?v=<?php echo time(); ?>"></script>
</body>
</html>