<?php 
	session_start();
	if (!isset($_SESSION["expertID"]))
		exit("An error occured: user not defined");

	$expertID = $_SESSION["expertID"];
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Create Mentoring Program</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/expert/profile/create_page.css">
</head>
<body>
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="../search/searchasmentor.php">Find Business Problem</a>
				<a href="#">Create Mentoring Program</a>
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
			<input type="hidden" id="expert-id" value="<?php echo $expertID?>">
			
			<section id="create-program-info">
				<h1>Create Program Page</h1>
				<div class="short-input">
					<div class="left">
						<div class="program-type">
							<label for="program-type">Program Type: </label>
							<select id="program-type">
								<option value="general">general</option>
								<option value="specified">specified</option>
							</select>
						</div>

						<div class="program-name">
							<label for="program-name">Program Name: </label>
							<input type="text" id="program-name">
						</div>

						<div class="program-category">
							<label for="ajax-category">Category: </label>
							<select id="ajax-category"></select>
						</div>
					</div>

					<div class="right">
						<div class="program-viewlevel">
							<label for="view-level">View Level:</label>
							<select id="view-level">
								<option value="public">public</option>
								<option value="private">private</option>
							</select>
						</div>

						<div id="general-cost">
							<div class="program-mincost">
								<label for="min-cost">Minimum Cost:</label>
					            <input type="number" id="min-cost">
							</div>

							<div class="program-maxcost">
					            <label for="max-cost">Maximum Cost:</label>
					            <input type="number" id="max-cost">
							</div>
			            </div>

			            <div id="specified-cost" style="display:none">
			            	<div class="program-cost">
					            <label for="cost">Cost:</label>
					            <input type="number" id="cost">
			            	</div>
			            </div>

			            <div class="program-duration">
				            <label for="program-duration">Program Duration:</label>
				            <input type="number" id="program-duration">
			            </div>
					</div>
				</div>

	            <div class="program-focus">
		            <label for="focus-problem-desc">Focus Problem Description:</label><br>
		            <span class="textarea" role="textbox" contenteditable id="focus-problem-desc"></span>
	            </div>
			</section>

			<section class="program-about">
	            <label for="about">About:</label><br>
	            <span class="textarea" role="textbox" contenteditable id="about"></span>
			</section>

			<section class="program-outcome">
	            <label for="expected-outcome">Expected Outcome:</label><br>
	            <span class="textarea" role="textbox" contenteditable id="expected-outcome"></span>
			</section>

			<section class="program-proposal">
	            <label for="file">Mentoring Program Proposal:</label>
				<button id="choose-file-btn">Choose File</button>
				<button id="remove-file-btn" style="display:none">Remove File</button>
				<span id="file-name">No file chosen</span>
				<input type="file" id="file" style="display:none">

			</section>
	        <input type="submit" id="create-btn" value="Create Program">
		</form>
	</main>	

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/expert/profile/create_page.js?v=<?php echo time(); ?>"></script>
</body>
</html>