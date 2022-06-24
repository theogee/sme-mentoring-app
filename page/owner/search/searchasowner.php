<?php 
	session_start();
	if (!isset($_SESSION["ownerID"]))
		exit("An error occured: user not defined");
	if (isset($_SESSION["programPageID"]));
		unset($_SESSION["programPageID"]);
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Find A Mentor</title>
	<link rel="stylesheet" href="../../../css/general/general.css">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/owner/search/searchasowner.css">
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
<script src="../../../js/owner/search/searchasowner.js?v=<?php echo time() ?>"></script>
<body>
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="#">Find A Mentor</a>
				<a href="../profile/create_page.php">Create Business Page</a>
			</div>
			<div class="right-column">
				<a href="../profile/profile.php"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<br>
	<form action="searchasowner.php" method="post">
		Search by :
		<select class="sort" name="sort">
			<option>Find All</option>
			<option value="a">Program Name</option>
			<option value="b">Category</option>
			<option value="c">Location</option>
			<option value="d">Min. Price</option>
			<option value="e">Max Price</option>
		</select>
		<br>
		<div class="a b box">
			Search : <input type="text" name="search">
		</div>
		<br>
		<div class="c box">
			Province:
			<select name="provinceID" id="ajax-province"></select>
			<br>
			City:
			<select name="cityID" id="ajax-city" disabled>
				<option value="" disabled selected hidden>please choose...</option>
			</select>
        <br><br>
		</div>
		<div class="d e box">
			<input type="radio" name="order" value="LH">Low To High
			<input type="radio" name="order" value="HL">High To Low
		</div>
		<input type="submit" value="Submit">	
	</form>

	<div class="line-splitter">
		<p>Available Program Page</p>
	</div>

	<div class="program-list">
		<?php
			require_once "../../../utility/dbconn.php";
			$conn = connect();
			
		    if(isset($_POST['search'])){
				$sort = $_POST['sort'];
				$search = $_POST['search'];
				if($sort == "a") {
					$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.program_name LIKE '%$search%' AND general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id";
				} else if($sort == "b"){
					$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE business_category.name LIKE '%$search%' AND general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id";
				} else if($sort == "c"){
					$provinceID = (isset($_POST["provinceID"])) ? $_POST["provinceID"] : '';
					if ($provinceID != '') {
						$cityID = (isset($_POST["cityID"])) ? $_POST["cityID"] : '';
						if($cityID == "") {
							$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND city.province_id = $provinceID AND general_prog_page.category_id = business_category.id";
						} else {
							$cityID = (int) $cityID;
							$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND city.id= $cityID AND general_prog_page.category_id = business_category.id";
						}
					} else
						$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id";
				} else if($sort == "d"){
					$order = $_POST['order'];
					if($order == "LH") {
						$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id ORDER BY general_prog_page.min_cost ASC";
					} else if($order == "HL") {
						$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id ORDER BY general_prog_page.min_cost DESC";
					} else {
						$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id ORDER BY general_prog_page.min_cost ASC";
					}
				} else if($sort == "e"){
					$order = $_POST['order'];
					if($order == "LH") {
						$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id ORDER BY general_prog_page.max_cost ASC";
					} else if($order == "HL") {
						$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id ORDER BY general_prog_page.max_cost DESC";
					} else {
						$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id ORDER BY general_prog_page.max_cost ASC";
					}
				} else {
					$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id";
				}
		    
				$result = $conn->query($sql);
				if (!$result) {
					trigger_error('Invalid query: ' . $conn->error);
				} else if ($result->num_rows > 0) {
						while($row = $result->fetch_array(MYSQLI_ASSOC)){
							$id = $row["id"];
							$name = $row["name"];
							$Pname = $row["program_name"];
							$prov = $row["province"];
							$city = $row["city"];
							$Fdesc = $row["focus_problem_desc"];
							$duration = $row["program_duration"];
							$mcost = $row["min_cost"];
							$Mcost = $row["max_cost"];
							$category = $row["category"];
							$viewLevel = $row["view_level"];
							echo "
								<div class='program-card'>
									<p class='program-name'>$Pname <span class='program-category'>$category</span> <span class='program-viewlevel'>$viewLevel</span><br></p>
									<p>$prov, $city</p>
									<p>
										Duration: $duration months<br>
										Cost: Rp. ".number_format($mcost, 0, ',', '.')." - Rp. ".number_format($Mcost, 0, ',', '.')."
									</p>
									<p>Focus Problem:<br>$Fdesc</p>
									<a class='program-page-link' href='program_page.php?pageID=$id&pageTitle=$Pname&type=general'>Full Page</a>
								</div>
							";
						}
				} else{
					echo "<p>No matches found</p>";
				}
			} else {
				$sql = "SELECT general_prog_page.id, business_category.name, general_prog_page.program_name, province.name AS province, city.name AS city, general_prog_page.focus_problem_desc, general_prog_page.program_duration, general_prog_page.min_cost, general_prog_page.max_cost, business_category.name as category, general_prog_page.view_level as view_level FROM `general_prog_page`, `business_category`, `province`, `city`, `expert` WHERE general_prog_page.view_level = 'public' AND general_prog_page.category_id = business_category.id AND province.id = city.province_id AND general_prog_page.expert_id = expert.id AND expert.city_id = city.id AND general_prog_page.category_id = business_category.id";
				$result = $conn->query($sql);
				if (!$result) {
					trigger_error('Invalid query: ' . $conn->error);
				} else if ($result->num_rows > 0) {
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						$id = $row["id"];
						$name = $row["name"];
						$Pname = $row["program_name"];
						$prov = $row["province"];
						$city = $row["city"];
						$Fdesc = $row["focus_problem_desc"];
						$duration = $row["program_duration"];
						$mcost = $row["min_cost"];
						$Mcost = $row["max_cost"];
						$category = $row["category"];
						$viewLevel = $row["view_level"];
						echo "
							<div class='program-card'>
								<p class='program-name'>$Pname <span class='program-category'>$category</span> <span class='program-viewlevel'>$viewLevel</span><br></p>
								<p>$prov, $city</p>
								<p>
									Duration: $duration months<br>
									Cost: Rp. ".number_format($mcost, 0, ',', '.')." - Rp. ".number_format($Mcost, 0, ',', '.')."
								</p>
								<p>Focus Problem:<br>$Fdesc</p>
								<a class='program-page-link' href='program_page.php?pageID=$id&pageTitle=$Pname&type=general'>Full Page</a>
							</div>
						";
					}
				}
			}
		?>
	</div>
</body>
</html>
