<?php 
	session_start();
	if (!isset($_SESSION["ownerID"]))
		exit("An error occured: user not defined");
	if (!isset($_SESSION["pageID"]))
		exit("An error occured: page not defined");

	$pageID = $_SESSION["pageID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Page</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/profile/edit_page.css">
</head>
<body>
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
	<br>
	<main>
		<form>
			<input type="hidden" id="page-id" value="<?php echo $pageID; ?>">

			<section id="edit-business-info">
				<h1>Edit Business Page</h1>
				<div class="short-input">
					<div class="left">
						<div class="business-name">
							<label for="ajax-business-name">Business Name:</label>
							<input type="text" id="ajax-business-name">
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
							<select id="ajax-city"></select>
						</div>
					</div>
				</div>
				
				<div class="business-short-problem">
					<label for="ajax-short-problem-desc">Short Problem Description:</label><br>
					<span class="textarea" id="ajax-short-problem-desc" role="textbox" contenteditable></span>
				</div>
			</section>

			<section id="edit-business-problem">
				<label for="ajax-long-problem-desc">Business Problem:</label><br>
				<span class="textarea" role="textbox" contenteditable id="ajax-long-problem-desc"></span>
			</section>

			<section id="edit-business-desc">
				<div class="business-about">
					<label for="ajax-about">About:</label><br>
					<span class="textarea" role="textbox" contenteditable id="ajax-about"></span>
				</div>

				<div class="business-proposal">
					<label for="file">Business Problem Proposal:</label><br>
					<input type="file" id="file" style="display:none">
					<input type="hidden" id='init-file-path' value=''>

					<div id="file-btn-container">
						<button id="choose-file-btn">Choose File</button>
						<button id="remove-file-btn" style="display:none">Remove File</button>
						<button id="remove-init-file-btn" style="display:none">Remove File</button>
						<span id="display-text">no file chosen</span>
					</div>
				</div>
			</section>

			<input type="submit" id="update-btn" value="Update">
		</form>
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/owner/profile/edit_page.js?v=<?php echo time(); ?>"></script>
</body>
</html>