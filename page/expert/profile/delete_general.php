<?php 
	session_start();
	if (!isset($_SESSION["pageID"]))
		exit("An error occured: page not defined");

	$pageID = $_SESSION["pageID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Delete Page</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/expert/profile/delete_general.css">
</head>
<body>
	<main>
		<div id="ajax-container" data-page-id="<?php echo $pageID; ?>"></div>
		<p class="confirmation-text">You can't recover this page once you delete it. Do you want to procede?</p>
		<button id="delete-yes">yes</button>
		<button id="delete-no">no, go back</button>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/expert/profile/delete_general.js?v=<?php echo time(); ?>"></script>
</body>
</html>