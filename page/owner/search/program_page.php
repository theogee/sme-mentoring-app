<?php 
	session_start();
	if (!isset($_SESSION["ownerID"]))
		exit("An error occured: user not defined");

	$ownerID = $_SESSION["ownerID"];

	// from expert
	$_SESSION["programPageID"] = $pageID = $_GET["pageID"];
	$pageTitle = $_GET["pageTitle"];
	$type = $_GET["type"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $pageTitle; ?></title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/search/program_page.css">
</head>
<body data-page-id="<?php echo $pageID; ?>" data-type="<?php echo $type ?>">
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
	<br>
	<main>
		<section id="program-info">
			<div id="ajax-program-info"></div>
		</section>
		<section id="program-desc">
			<h2>Cost</h2>
			<div id="ajax-cost"></div>
			<h2>Duration</h2>
			<div id="ajax-duration"></div>
			<h2>About</h2>
			<div id="ajax-about"></div>
			<h2>Expected Outcome</h2>
			<div id="ajax-expected-outcome"></div>
			<h2>Mentoring Program Proposal</h2>
			<div id="ajax-proposal"></div>
		</section>
		<br>
		<section id="send-interest-request">
			<h2>Match with your business problem?</h2>
			<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolores eveniet quas hic itaque ex ea, magnam voluptatem, eum. Dicta, exercitationem ea voluptas eaque magni, sit non mollitia sed beatae. Aspernatur.</p>
			<button id="send-interest-request-btn">Send an interest request!</button>
			<br><br>
			<div id="business-list-dialog" data-owner-id="<?php echo $ownerID; ?>" style="display:none">
				<p class="select-text">Select your business</p>
				<button id="cancel-btn">X</button>
				<div id="ajax-container">?</div>
			</div>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/owner/search/program_page.js?v=<?php echo time(); ?>"></script>
</body>
</html>