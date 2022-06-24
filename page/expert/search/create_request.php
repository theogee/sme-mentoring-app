<?php 
	session_start();
	if (!isset($_SESSION["expertID"]))
		exit("An error occured: user not defined");
	if (!isset($_SESSION["businessPageID"]) && !isset($_GET["programPageID"]) && !isset($_GET["programPageType"]))
		exit("An error occured: page not defined");

	$programPageID = $_GET["programPageID"];
	$programPageType = $_GET["programPageType"];
	$businessPageID = $_SESSION["businessPageID"];
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<title>Create offer request</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/expert/search/create_request.css">
 </head>
 <body data-program-page-id="<?php echo $programPageID ?>" data-program-page-type="<?php echo $programPageType ?>" data-business-page-id="<?php echo $businessPageID ?>">
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="searchasmentor.php">Find Business Problem</a>
				<a href="../profile/create_page.php">Create Mentoring Program</a>
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
			<div id="ajax-program-page"></div>
			<h2>To:</h2>
			<div id="ajax-business-page"></div>
			<br>
			<button id="send-btn">Send request</button>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/expert/search/create_request.js?v=<?php echo time(); ?>"></script>
 </body>
 </html>