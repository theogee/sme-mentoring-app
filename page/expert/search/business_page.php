<?php 
	session_start();
	if (!isset($_SESSION["expertID"]))
		exit("An error occured: user not defined");

	$expertID = $_SESSION["expertID"];

	// from business owner
	$_SESSION["businessPageID"] = $pageID = $_GET["pageID"];
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
	<link rel="stylesheet" type="text/css" href="../../../css/expert/search/business_page.css">
 </head>
 <body data-page-id="<?php echo $pageID ?>">
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
	<br>
	<main>
		<section id="business-info">
			<div id="ajax-business-info">?</div>
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
		<br>
		<section id="send-offer-request">
			<h2>Have a solution for this business?</h2>
			<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolores eveniet quas hic itaque ex ea, magnam voluptatem, eum. Dicta, exercitationem ea voluptas eaque magni, sit non mollitia sed beatae. Aspernatur.</p>
			<button id="send-offer-request-btn">Send an offer request!</button>
			<br><br>
			<div id="program-list-dialog" data-expert-id="<?php echo $expertID ?>" style="display:none">
				<p class="select-text">Select your program</p>
				<button id="cancel-btn">X</button>
				<br><br>
				<nav>
					<a id="general-prog-link" class="selected-link" href="#program-list-dialog">General Program</a>
				    <a id="specified-prog-link" href="#program-list-dialog">Specified Program</a>
				</nav>
				<div id="ajax-container">?</div>
			</div>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/expert/search/business_page.js?v=<?php echo time(); ?>"></script>
 </body>
 </html>