<?php 
	session_start();
	if (!isset($_SESSION["expertID"]))
		exit("An error occured: user not defined");

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Find Business Problem</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/expert/search/searchasmentor.css">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        var targetBox = $("." + inputValue);
        $(".con").not(targetBox).hide();
        $(targetBox).show();
    });
    $("select").change(function(){
		const option = $("select").val();
		if (option) {
			$(".box").not("."+option).hide();
			$("." + option).show();
		} else {
			$(".box").hide();
		}
    }).change();
});
</script>
<script src="../../../js/expert/search/searchasmentor.js?v=<?php echo time() ?>"></script>
<body>
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="#">Find Business Problem</a>
				<a href="../profile/create_page.php">Create Mentoring Program</a>
			</div>
			<div class="right-column">
				<a href="../profile/profile.php"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<br>
	<form action="searchasmentor.php" method="post">    
	    Search by :
		<select class="sort" name="sort">
			<option>Find All</option>
			<option value="1">Business Name</option>
			<option value="2">Category</option>
			<option value="3">Location</option>
			<option value="4">Problem</option>
		</select>
		<br>
		<div class="1 2 4 box">
			Search : <input type="text" name="search">
		</div>
		<br>
		<div class="3 box">
			Province:
			<select name="provinceID" id="ajax-province"></select>
			<br>
			City:
			<select name="cityID" id="ajax-city" disabled>
				<option value="" disabled selected hidden>please choose...</option>
			</select>
		</div>
		<input type="submit" value="Submit">	
	</form>

	<div class="line-splitter">
		<p>Available Business Page</p>
	</div>

	<div class="business-list">
		<?php

			require_once "../../../utility/dbconn.php";
			$conn = connect();
			
		    if(isset($_POST['search'])){
				$sort = $_POST['sort'];
				$search = $_POST['search'];

				if($sort == "1") {
					$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id AND business_name LIKE '%$search%'";
				} else if($sort == "2"){
					$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id AND business_category.name LIKE '%$search%'";
				} else if($sort == "3"){
					$cityID = (isset($_POST["cityID"])) ? $_POST["cityID"] : '';
					if($cityID == "") {
						if (isset($_POST["provinceID"])) {
							$provinceID = (int) $_POST["provinceID"];
							$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id AND city.province_id = $provinceID";
						} else {
							$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id";
						}
					} else {
						$cityID = (int) $cityID;
						$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id AND business_page.city_id = $cityID";
					}
				} else if($sort == "4"){
					$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id AND short_problem_desc LIKE '%$search%'";
				} else {
					$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id";
				}
				$result = $conn->query($sql);
				if (!$result) {
					trigger_error('Invalid query: ' . $conn->error);
				} else if ($result->num_rows > 0) {
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							$id = $row["id"];
							$category = $row["category"];
							$viewLevel = $row["view_level"];
							$bName = $row["business_name"];
							$oName = $row["owner"];
							$city = $row["city"];
							$prov = $row["province"];
							$sDesc = $row["short_problem_desc"];
							echo "
								<div class='business-card'>
									<p class='business-name'>$bName <span class='business-category'>$category</span> <span class='business-viewlevel'>$viewLevel</span></p>
									<p>$prov, $city</p>
									<p>Problem:<br>$sDesc</p>
									<a class='business-page-link' href='business_page.php?pageID=$id&pageTitle=$bName'>Full Page</a>
								</div>
							";
						}
				} else{
					echo "<p>No matches found</p>";
				}
			} else {
				$sql = "SELECT business_page.id, business_category.name AS category, business_page.view_level, business_name, business_owner.name AS owner, city.name AS city, province.name AS province, short_problem_desc FROM business_page, business_owner, province, city, business_category WHERE view_level = \"public\" AND business_owner.id = business_page.owner_id AND business_owner.city_id = city.id AND province.id = city.province_id AND business_page.category_id = business_category.id";
				$result = $conn->query($sql);
				if (!$result) {
					trigger_error('Invalid query: ' . $conn->error);
				} else if ($result->num_rows > 0) {
					?>
					<table style="width:50%">
						<tr>
							<th>ID</th>
							<th>Category</th>
							<th>Business Name</th>
							<th>Owner Name</th>
							<th>Location</th>
							<th>Problem</th>
						</tr>
						<?php
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							$id = $row["id"];
							$category = $row["category"];
							$viewLevel = $row["view_level"];
							$bName = $row["business_name"];
							$oName = $row["owner"];
							$city = $row["city"];
							$prov = $row["province"];
							$sDesc = $row["short_problem_desc"];
							echo "
								<div class='business-card'>
									<p class='business-name'>$bName <span class='business-category'>$category</span> <span class='business-viewlevel'>$viewLevel</span></p>
									<p>$prov, $city</p>
									<p>Problem:<br>$sDesc</p>
									<a class='business-page-link' href='business_page.php?pageID=$id&pageTitle=$bName'>Full Page</a>
								</div>
							";
						}
					?></table><?php
				}
			}
		?>
	</div>
</body>
</html>