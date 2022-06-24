<?php 
	session_start();
	if (!isset($_SESSION["admin"]))
		header("location: auth.php");		
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../css/general/general.css?v=<?php echo time() ?>">
	<link rel="stylesheet" href="../../css/admin/index.css?v=<?php echo time() ?>">
	<title>Admin Dashboard</title>
</head>
<body>
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<a href="#">dashboard</a>
			<button id="logout-btn">Log out</button>
		</nav>
	</header>
	<main>
		<section id="header">
			<h1>Admin Dashboard</h1>
		</section>
		<section id="dashboard">
			<nav>
				<a href="#" id="pending-transaction-link" class="selected-link">Pending Transaction</a>
				<a href="#" id="rejected-transaction-link">Rejected Transaction</a>
				<div id="pending-transaction-option">
					<a id="pt-ir-link" href="#" class="selected-link">Interest Request</a>
					<a id="pt-or-link" href="#">Offer Request</a>
				</div>
				<div id="rejected-transaction-option" style="display:none">
					<a id="rt-ir-link" href="#" class="selected-link">Interest Request</a>
					<a id="rt-or-link" href="#">Offer Request</a>
				</div>
			</nav>
			<div id="ajax-container"></div>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../js/admin/index.js?v=<?php echo time(); ?>"></script>
</body>
</html>