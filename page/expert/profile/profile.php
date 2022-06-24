<?php 
	session_start();
	if (isset($_SESSION["pageID"]))
		unset($_SESSION["pageID"]);
	if (!isset($_SESSION["expertID"])) 
		exit("An error occured: user not defined. <a href='../../login.html'>Click here to login</a>");

	require_once "../../../utility/dbconn.php";
	$dbconn = connect();

	$expertID = $_SESSION["expertID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/expert/profile/profile.css">
</head>
<body>
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="../search/searchasmentor.php">Find Business Problem</a>
				<a href="create_page.php">Create Mentoring Program</a>
			</div>
			<div class="right-column">
				<a href="#"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<main>
		<section id="notification">
			<div id="ajax-notif"></div>
		</section>
        <section id="expert-info">
            <?php 
                $query = "SELECT * FROM expert WHERE id = $expertID";

                $result = $dbconn->query($query);
                $row = $result->fetch_assoc();

                echo "
                <p class='user-type'>Expert</p>
                <div class='info-wrapper'>
	                <h2 id='expert-name' data-expert-id='$expertID'>" . $row['name'] . "</h2>
	                <a class='edit-profile' href='edit_profile.php'>Edit Profile</a>
	            </div>
	            <h3 class='expert-occupation'>" . $row['company'] . "|" . $row['profession'] . "</h3>
	            ";
            ?>
        </section>
        <br>
        <section id="dashboard">
	    	<nav class="dashboard-nav">
	    		<div class="dashboard-wrapper">
					<a id="general-prog-link" class="selected-link" href="#">General Program</a>
					<a id="specified-prog-link" href="#">Specified Program</a>
					<a id="offer-request-link" href="#">Offer Request</a>
					<a id="mentor-room-link" href="#">Mentor Room</a>
	    		</div>
			</nav>
			<div id="ajax-container">?</div>
        </section>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/expert/profile/profile.js?v=<?php echo time(); ?>"></script>
</body>
</html>