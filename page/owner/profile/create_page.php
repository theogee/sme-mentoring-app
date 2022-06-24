<?php 
	session_start();
	if (!isset($_SESSION["ownerID"]))
		exit("An error occured: user not defined");

	$ownerID = $_SESSION["ownerID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Create Business Page</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/profile/create_page.css">
</head>
<body>
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="../search/searchasowner.php">Find A Mentor</a>
				<a href="#">Create Business Page</a>
			</div>
			<div class="right-column">
				<a href="profile.php"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<br>
	<main>
		<form autocomplete="off">
			<input type="hidden" id="owner-id" value="<?php echo $ownerID; ?>">

			<section id="create-business-info">
				<h1>Create Business Page</h1>
				<div class="short-input">
					<div class="left">
						<div class="business-name">
							<label for="business-name">Business Name:</label>
							<input type="text" id="business-name">
						</div>

						<div class="business-category">
							<label for="ajax-category">Category:</label>
							<select id="ajax-category"></select>
						</div>

						<div class="business-viewlevel">
							<label for="view-level">View Level:</label>
							<select id="view-level">
								<option value="public">public</option>
								<option value="private">private</option>
							</select>
						</div>
					</div>
					
					<div class="right">
						<div class="business-province">
							<label for="ajax-province">Province:</label>
							<select id="ajax-province"></select>
						</div>

						<div class="business-city">
							<label for="ajax-city">City:</label>
							<select id="ajax-city" disabled>
								<option value="" disabled selected hidden>please choose...</option>
							</select>
						</div>
					</div>
				</div>

				<div class="business-short-problem">
					<label for="short-problem-desc">Short Problem Description:</label><br>
					<span class="textarea" role="textbox" contenteditable id="short-problem-desc"></span>
				</div>
			</section>


			<section id="create-business-problem">
				<label for="long-problem-desc">Business Problem:</label><br>
				<span class="textarea" role="textbox" contenteditable id="long-problem-desc"></span>
			</section>
			

			<section id="create-business-desc">
				<div class="business-about">
					<label for="about">About:</label><br>
					<span class="textarea" role="textbox" contenteditable id="about"></span>
				</div>

				<div class="business-proposal">
					<label for="file">Business Problem Proposal:</label>
					<button id="choose-file-btn">Choose File</button>
					<button id="remove-file-btn" style="display:none">Remove File</button>
					<span id="file-name">No file chosen</span>
					<input type="file" id="file" style="display:none">
				</div>
			</section>

			<input type="submit" id="create-btn" value="Create">
		</form>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/owner/profile/create_page.js?v=<?php echo time(); ?>"></script>
</body>
</html>