<?php 
	session_start();
	if (!isset($_SESSION["expertID"]))
		exit("An error occured: user not defined");
	if (!isset($_SESSION["requestID"]))
		exit("An error occured: page not defined");

	$requestID = $_SESSION["requestID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Close Offer Request</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/expert/profile/close_offer_req.css">
</head>
<body>
	<main>
		<div id="ajax-container" data-request-id="<?php echo $requestID; ?>">
			<div id="ajax-request-header"></div>
			<h2>From:</h2>
			<div id="ajax-program-page"></div>
			<h2>To:</h2>
			<div id="ajax-business-page"></div>
		</div>
		<p class="confirmation-text">You can't recover this request page once you close it. Do you want to procede?</p>
		<button id="delete-yes">yes</button>
		<button id="delete-no">no, go back</button>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   	<script src="../../../js/expert/profile/close_offer_req.js?v=<?php echo time(); ?>"></script>
</body>
</html>