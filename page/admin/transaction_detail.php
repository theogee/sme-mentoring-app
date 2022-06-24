<?php 
	session_start();
	if (!isset($_SESSION["admin"]))
		exit("<p>An error occured: user not defined</p>");
	if (!isset($_GET["requestID"]) && !isset($_GET["requestType"]))
		exit("<p>An error occured: page not defined</p>");

	$requestID = $_GET["requestID"];
	$requestType = $_GET["requestType"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Transaction Detail</title>
</head>
<body data-request-id="<?php echo $requestID ?>" data-request-type="<?php echo $requestType ?>">
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<a href="index.php">dashboard</a>
			<button id="logout-btn">Log out</button>
		</nav>
	</header>
	<main>
		<section id="receipt">
			<h2>Receipt</h2>
			<div id="ajax-receipt"></div>
			<div id="ajax-pot-info"></div>
		</section>
		<section id="request-info">
			<h2>Participant</h2>
			<h3>Business</h3>
			<div id="ajax-business-page"></div>
			<h3>Program</h3>
			<div id="ajax-specified-page"></div>
		</section>
		<section id="verification">
			<button id="verify-btn">verify</button>
			<button id="reject-btn">reject</button>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   	<script src="../../js/admin/transaction_detail.js?v=<?php echo time(); ?>"></script>
</body>
</html>