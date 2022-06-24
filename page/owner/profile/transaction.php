<?php 
	session_start();
	if (!isset($_SESSION["ownerID"])) 
		exit("An error occured: user not defined. <a href='../../login.html'>Click here to login</a>");

	$requestID = $_SESSION["requestID"];
	$req = $_GET["req"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Establish Mentor Room</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/profile/transaction.css">
</head>
<body data-request-id="<?php echo $requestID ?>" data-req="<?php echo $req ?>">
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
		<section id="receipt">
			<h1>Establishing Mentor Room</h1>
			<h2>Receipt</h2>
			<div id="ajax-receipt"></div>
			<p>Transfer to: 10827182109 (IQONIQ)</p>
		</section>
		<section id="request-info">
			<h2>Participant</h2>
			<h3>Business</h3>
			<div id="ajax-business-page"></div>
			<h3>Program</h3>
			<div id="ajax-specified-page"></div>
		</section>
		<section id="pot-interface" style="display:none">
			<h2>Upload Proof of Transaction</h2>
			<button id="choose-file-btn">browse</button>
			<button id="remove-file-btn" style="display:none">Remove File</button>
			<span id="file-name">No file chosen</span>
			<input type="file" id="file" style="display:none">
			<br><br>
			<button id="upload-btn">upload</button>
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   	<script src="../../../js/owner/profile/transaction.js?v=<?php echo time(); ?>"></script>
</body>
</html>