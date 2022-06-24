<?php 
	session_start();

	if (!isset($_GET["functionCall"]) && !isset($_POST["functionCall"]))
		exit("An error occured: bad request");

	require_once "dbconn.php";
	$dbconn = connect();

	require_once "../ws/db/ChatManager.php";

	// UTILITY
	function uploadResourceFile(&$file, &$availableSpace) {
		$fileName = $file["name"];
		$fileTmpLocation = $file["tmp_name"];
		$fileSize = $file["size"];
		$fileError = $file["error"];

		$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

		$allowedExt = array("pdf", "docx", "doc", "mp4", "zip", "rar", "png", "jpg", "jpeg", "gif", "mp3", "pptx", "xlsx");

		$response = array("status"=>"error", "message"=>"");

		if (in_array($fileExt, $allowedExt)) {
			if ($fileError == 0) {
				if ($fileSize <= $availableSpace) {
					$newFileName = uniqid(true) . "." . $fileExt;
					$fileDestination = "../uploads/resources/" . $newFileName;
					move_uploaded_file($fileTmpLocation, $fileDestination);

					$response["status"] = "success";
					$response["message"] = "upload success";
					$response["fileName"] = $newFileName;

				} else {
					$response["message"] = "An error occured: file size exceed available space";
				}
			} else {
				$response["message"] = "An error occured: uploading file";
			}
		} else {
			$response["message"] = "An error occured: file format not supported";
		}

		return $response;
	}

	function uploadFile(&$file, $dir) {

		$fileName = $file["name"];
		$fileTmpLocation = $file["tmp_name"];
		$fileSize = $file["size"];
		$fileError = $file["error"];
		// $fileType = $file["type"];
		
		$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

		$allowedExt = array("pdf", "docx", "doc");

		$response = array("status"=>"error", "message"=>"");

		if (in_array($fileExt, $allowedExt)) {
			if ($fileError == 0) {
				if ($fileSize < 5000000) {
					$newFileName = uniqid(true) . "." . $fileExt;
					$fileDestination = $dir . $newFileName;
					move_uploaded_file($fileTmpLocation, $fileDestination);

					$response["status"] = "success";
					$response["message"] = "upload success";
					$response["fileName"] = $newFileName;

				} else {
					$response["message"] = "An error occured: file size exceed 5MB";
				}
			} else {
				$response["message"] = "An error occured: uploading file";
			}
		} else {
			$response["message"] = "An error occured: file format not supported";
		}

		return $response;
	}

	function uploadImage(&$file, $dir) {

		$fileName = $file["name"];
		$fileTmpLocation = $file["tmp_name"];
		$fileSize = $file["size"];
		$fileError = $file["error"];
		// $fileType = $file["type"];
		
		$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

		$allowedExt = array("jpg", "jpeg", "pdf", "png");

		$response = array("status"=>"error", "message"=>"");

		if (in_array($fileExt, $allowedExt)) {
			if ($fileError == 0) {
				if ($fileSize < 5000000) {
					$newFileName = uniqid(true) . "." . $fileExt;
					$fileDestination = $dir . $newFileName;
					move_uploaded_file($fileTmpLocation, $fileDestination);

					$response["status"] = "success";
					$response["message"] = "upload success";
					$response["fileName"] = $newFileName;

				} else {
					$response["message"] = "An error occured: file size exceed 5MB";
				}
			} else {
				$response["message"] = "An error occured: uploading file";
			}
		} else {
			$response["message"] = "An error occured: file format not supported";
		}

		return $response;
	}

	// GET
	// __ GENERAL
	function getProvinceList() {
		global $dbconn;

		$query = "SELECT * FROM province";

		$result = $dbconn->query($query);

		$data = array();

		while ($row = $result->fetch_assoc()) {
			array_push($data, array("provinceID"=>$row['id'], "provinceName"=>$row['name']));
		}

		$json = json_encode($data);
		echo $json;
	}

	function getCityList() {
		global $dbconn;

		$provinceID = $_GET["provinceID"];

		$query = "SELECT * FROM city WHERE province_id = $provinceID ORDER BY name";

		$result = $dbconn->query($query);

		$data = array();

		while ($row = $result->fetch_assoc()) {
			array_push($data, array("cityID"=>$row['id'], "cityName"=>$row['name']));
		}

		$json = json_encode($data);
		echo $json;
	}

	function getCategoryList() {
		global $dbconn;

		$query = "SELECT * FROM business_category";

		$result = $dbconn->query($query);

		$data = array();

		while ($row = $result->fetch_assoc()) {
			array_push($data, array(
				"categoryID"=>$row['id'],
				"categoryName"=>$row['name']
			));
		}

		$json = json_encode($data);
		echo $json;
	}

	function checkSpecifiedProgramPageAvailabilityForInterestRequest() {
		global $dbconn;

		$requestID = (int) $_GET["requestID"];

		$query = "SELECT specified_prog_page_id FROM interest_request WHERE ir_id = $requestID";

		$result = $dbconn->query($query);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			if ($row["specified_prog_page_id"] !== null) 
				$data = array("status"=>"available", "message"=>"expert has provided specified program page");
			else 
				$data = array("status"=>"not available", "message"=>"expert hasn't provide specified program page");

			$json = json_encode($data);
			echo $json;
		}
	}

	function checkSpecifiedProgramPageAvailabilityForOfferRequest() {
		global $dbconn;

		$requestID = (int) $_GET["requestID"];

		$query = "SELECT specified_prog_page_id FROM offer_request WHERE or_id = $requestID";

		$result = $dbconn->query($query);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			if ($row["specified_prog_page_id"] !== null)
				$data = array("status"=>"available", "message"=>"expert has provided specified program page");
			else
				$data = array("status"=>"not available", "message"=>"expert hasn't provide specified program page");

			$json = json_encode($data);
			echo $json;
		}
	}

	function getInterestRequestPage() {
		global $dbconn;

		$requestID = $_GET["requestID"];

		$query = "
		SELECT ir.*, bp.owner_id, gpp.expert_id 
		FROM interest_request ir, business_page bp, general_prog_page gpp 
		WHERE ir.business_page_id = bp.id AND ir.general_prog_page_id = gpp.id AND ir.ir_id = $requestID
		";

		$result = $dbconn->query($query);

		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$data = array("status"=>"success",
						  "businessPageID"=>$row["business_page_id"], 
						  "generalPageID"=>$row["general_prog_page_id"],
						  "specifiedPageID"=>$row["specified_prog_page_id"],
						  "ownerID"=>$row["owner_id"],
						  "expertID"=>$row["expert_id"],
						  "potPath"=>$row["pot_path"],
						  "transactionDate"=>$row["transaction_date"],
						  "transactionStatus"=>$row["transaction_status"]);

		} else {
			$data = array("status"=>"error", "message"=>"request page not found");
		}

		$json = json_encode($data);
		echo $json;
	}

	function getOfferRequestPage() {
		global $dbconn;

		$requestID = (int) $_GET["requestID"];

		$query = "
		SELECT ofr.*, thisExpert.expert_id, bp.owner_id
		FROM offer_request ofr,
		-- we don't know the init program type, it may be null so it's better fetch the expert id from both general and specified
		(
		    SELECT ofr.or_id, gpp.expert_id 
		    FROM offer_request ofr, general_prog_page gpp 
		    WHERE ofr.or_id = $requestID AND gpp.id = ofr.general_prog_page_id
			UNION
			SELECT ofr.or_id, spp.expert_id 
		    FROM offer_request ofr, specified_prog_page spp 
		    WHERE ofr.or_id = $requestID AND spp.id = ofr.specified_prog_page_id
		) thisExpert, business_page bp
		WHERE ofr.or_id = thisExpert.or_id AND ofr.business_page_id = bp.id AND ofr.or_id = $requestID";

		$result = $dbconn->query($query);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$data = array("status"=>"success",
						  "generalPageID"=>$row["general_prog_page_id"],
						  "specifiedPageID"=>$row["specified_prog_page_id"],
						  "businessPageID"=>$row["business_page_id"],
						  "expertID"=>$row["expert_id"],
						  "ownerID"=>$row["owner_id"],
						  "potPath"=>$row["pot_path"],
						  "transactionDate"=>$row["transaction_date"],
						  "transactionStatus"=>$row["transaction_status"]);
		}
		else {
			$data = array("status"=>"error", "message"=>"request page not found");
		}

		$json = json_encode($data);
		echo $json;
	}

	function getMentorRoom() {
		global $dbconn;

		$roomID = (int) $_GET["roomID"];

		$query = "SELECT * FROM mentor_room WHERE room_id = $roomID";

		$result = $dbconn->query($query);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$data = array(
				"status"=>"found",
				"roomID"=>$row["room_id"],
				"businessPageID"=>$row["business_page_id"],
				"specifiedPageID"=>$row["specified_prog_page_id"],
				"createdDate"=>$row["created_date"],
				"availableSpace"=>$row["resources_space"]
			);
		} else {
			$data = array("status"=>"not found", "message"=>"mentor room not found");
		}

		$json = json_encode($data);
		echo $json;
	}

	function getResources() {
		global $dbconn;

		$roomID = (int) $_GET["roomID"];

		$query = "
		SELECT r.*, bo.name as poster_name FROM resources r, business_owner bo WHERE r.poster_id = bo.id AND r.user_type = 'owner' AND r.room_id = $roomID
		UNION
		SELECT r.*, e.name as poster_name FROM resources r, expert e WHERE r.poster_id = e.id AND r.user_type = 'expert' AND r.room_id = $roomID
		ORDER BY resource_id DESC";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"resourceID"=>$row["resource_id"],
					"posterID"=>$row["poster_id"],
					"title"=>$row["title"],
					"message"=>$row["message"],
					"filePath"=>$row["file_path"],
					"createdDate"=>$row["created_date"],
					"fileName"=>$row["file_name"],
					"posterName"=>$row["poster_name"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	// __ ADMIN
	function getPendingTransactionFromInterestRequest() {
		global $dbconn;

		$query = "SELECT * FROM interest_request WHERE transaction_status = 'pending'";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], $row["ir_id"]);
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getPendingTransactionFromOfferRequest() {
		global $dbconn;

		$query = "SELECT * FROM offer_request WHERE transaction_status = 'pending'";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], $row["or_id"]);
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getRejectedTransactionFromInterestRequest() {
		global $dbconn;

		$query = "SELECT * FROM interest_request WHERE transaction_status = 'rejected'";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], $row["ir_id"]);
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getRejectedTransactionFromOfferRequest() {
		global $dbconn;

		$query = "SELECT * FROM offer_request WHERE transaction_status = 'rejected'";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], $row["or_id"]);
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	// __ OWNER
	function getOwnerInfo() {
		global $dbconn;

		$ownerID = $_GET["ownerID"];

		$query = "SELECT bo.*, city.name as city_name, province.id as province_id, province.name as province_name FROM business_owner bo, city, province WHERE bo.city_id = city.id AND city.province_id = province.id AND bo.id = $ownerID";

		$result = $dbconn->query($query);

		$row = $result->fetch_assoc();

		$data = array("ownerID"=>$ownerID, 
					  "cityID"=>$row['city_id'], 
					  "cityName"=>$row['city_name'],
					  "provinceID"=>$row['province_id'],
					  "provinceName"=>$row['province_name'],
					  "ownerPP"=>$row['owner_pp'],
					  "ownerName"=>$row['name'],
					  "ownerEmail"=>$row['email'],
					  "ownerPassword"=>$row['password']);

		$json = json_encode($data);
		echo $json;
	}

	function getBusinessPageList() {
		global $dbconn;

		$ownerID = $_GET["ownerID"];

		$query = "SELECT bp.*, bc.name AS category_name FROM business_page bp, business_category bc WHERE bp.category_id = bc.id AND bp.owner_id = $ownerID";

		$result = $dbconn->query($query);

		$data = array();

		while ($row = $result->fetch_assoc()) {
			array_push($data, array(
				"pageID"=>$row['id'],
				"businessName"=>$row['business_name'],
				"shortProblemDesc"=>$row['short_problem_desc'],
				"categoryName"=>$row['category_name'],
				"viewLevel"=>$row['view_level']
			));
		}

		$json = json_encode($data);
		echo $json;
	}

	function getBusinessPage($internal = false, $businessPageID = null) {
		global $dbconn;

		$pageID = ($internal) ? (int) $businessPageID : (int) $_GET["pageID"];

		$query = "SELECT bp.*, bc.name AS category_name, bo.name as owner_name, bo.email as owner_email, city.name AS city_name, province.name AS province_name, province.id AS province_id FROM business_page bp, business_category bc, business_owner bo, city, province WHERE bp.category_id = bc.id AND bp.owner_id = bo.id AND bp.city_id = city.id AND city.province_id = province.id AND bp.id = $pageID";

		$result = $dbconn->query($query);

		$row = $result->fetch_assoc();

		$data = array(
			"pageID"=>$row['id'],
			"ownerID"=>$row['owner_id'],
			"categoryID"=>$row['category_id'],
			"cityID"=>$row['city_id'],
			"provinceID"=>$row['province_id'],
			"ownerName"=>$row["owner_name"],
			"ownerEmail"=>$row["owner_email"],
			"businessName"=>$row['business_name'],
			"categoryName"=>$row['category_name'],
			"cityName"=>$row['city_name'],
			"provinceName"=>$row['province_name'],
			"shortProblemDesc"=>$row['short_problem_desc'],
			"longProblemDesc"=>$row['long_problem_desc'],
			"about"=>$row['about'],
			"viewLevel"=>$row['view_level'],
			"filePath"=>$row['proposal_path']
		);

		// true if used for internal (same file) calls
		if ($internal)
			return $data;

		$json = json_encode($data);
		echo $json;
	}

	function getInterestRequestList() {
		global $dbconn;

		$ownerID = $_GET["ownerID"];

		$query = "SELECT ir.*, bp.business_name FROM interest_request ir, business_page bp WHERE ir.business_page_id = bp.id AND bp.owner_id = $ownerID";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";

			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"requestID"=>$row["ir_id"],
					"businessPageID"=>$row["business_page_id"],
					"businessName"=>$row["business_name"],
					"programPageID"=>$row["general_prog_page_id"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	// used to prevent duplicate interest request from a particular business page to a particular program page
	// result will not show business page that already sent a previous request to that particular program page
	// use-case: when owner can choose their business page when sending an interest request
	function getAvailableBusinessPageListForProgramPage() {
		global $dbconn;

		$ownerID = $_GET["ownerID"];
		$programPageID = $_GET["programPageID"];

		$query = "
			SELECT bp.*, bc.name AS category_name, IFNULL(rc.no_of_request, 0) AS no_of_request
			FROM business_page bp
			LEFT JOIN business_category bc
			ON bp.category_id = bc.id
			-- get the number of request a business page has made to prevent more than 3 request sent
			LEFT JOIN
			(
			    SELECT business_page_id, COUNT(business_page_id) as no_of_request
				FROM interest_request
				GROUP BY business_page_id
			) rc
			ON bp.id = rc.business_page_id
			WHERE IFNULL(rc.no_of_request, 0) < 3 AND bp.owner_id = $ownerID 
			-- prevent request duplication
			AND bp.id NOT IN (SELECT ir.business_page_id FROM interest_request ir WHERE ir.general_prog_page_id = $programPageID)
		";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"pageID"=>$row['id'],
					"businessName"=>$row['business_name'],
					"shortProblemDesc"=>$row['short_problem_desc'],
					"categoryName"=>$row['category_name'],
					"viewLevel"=>$row['view_level']
				));
			}
		}

		$json = json_encode($data);
		echo $json;
		/*
			if not found
			case 1: owner does not own any business page
			case 2: all business page already made 3 request which is the max number of request 1 business page could make
			case 3: all business page already made a request to the particular program (LOL)
		*/

	}

	function checkBusinessPageInAnyRequest() {
		global $dbconn;

		$pageID = (int) $_GET["pageID"];

		// check in interest request
		$query = "
			SELECT business_page_id
			FROM interest_request
			WHERE business_page_id = $pageID
			GROUP BY business_page_id
		";
		$result = $dbconn->query($query);
		$rowInterestRequest = $result->num_rows;
		// check in offer request
		$query = "
			SELECT business_page_id
			FROM offer_request
			WHERE business_page_id = $pageID
			GROUP BY business_page_id
		";
		$result = $dbconn->query($query);
		$rowOfferRequest = $result->num_rows;
		// check in mentor room
		$query = "
		SELECT business_page_id
		FROM mentor_room
		WHERE business_page_id = $pageID
		GROUP BY business_page_id";
		$result = $dbconn->query($query);
		$rowMentorRoom = $result->num_rows;

		if ($rowInterestRequest == 0 && $rowOfferRequest == 0 && $rowMentorRoom == 0)
			$data = array("status"=>"not found", "message"=>"this business page doesn't have interest, offer request and mentor room");
		else
			$data = array("status"=>"found", "message"=>"this business page has interest, offer request, or mentor room");

		$json = json_encode($data);
		echo $json;
	}

	function getMentoringOfferList() {
		global $dbconn;

		$businessPageID = (int) $_GET["businessPageID"];

		$query = "SELECT * FROM offer_request WHERE business_page_id = $businessPageID";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";

			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"requestID"=>$row["or_id"],
					"generalPageID"=>$row["general_prog_page_id"],
					"specifiedPageID"=>$row["specified_prog_page_id"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getOwnerNotification() {
		global $dbconn;

		$ownerID = (int) $_GET["ownerID"];

		$query = "SELECT * FROM owner_notif WHERE owner_id = $ownerID ORDER BY notif_id DESC";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());
		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"notifID"=>$row["notif_id"],
					"notification"=>$row["notification"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getMentorRoomListForOwner() {
		global $dbconn;

		$ownerID = (int) $_GET["ownerID"];

		$query = "SELECT mr.*, bp.business_name FROM mentor_room mr, business_page bp WHERE mr.business_page_id = bp.id AND bp.owner_id = $ownerID";

		$result = $dbconn->query($query);
		$data = array("status"=>"not found", "list"=>array());
		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"roomID"=>$row["room_id"],
					"businessPageID"=>$row["business_page_id"],
					"specifiedPageID"=>$row["specified_prog_page_id"],
					"businessName"=>$row["business_name"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	// __ EXPERT
	function getGeneralProgramList() {
		global $dbconn;

		$expertID = $_GET["expertID"];

		$query = "SELECT gpp.*, bc.name as category_name FROM general_prog_page gpp, business_category bc WHERE gpp.category_id = bc.id AND gpp.expert_id = $expertID ORDER BY gpp.id ASC";

		$result = $dbconn->query($query);

		$data = array();

		while ($row = $result->fetch_assoc()) {
			array_push($data, array(
				"pageID"=>$row["id"],
				"programName"=>$row["program_name"],
				"focusProblemDesc"=>$row["focus_problem_desc"],
				"categoryName"=>$row["category_name"],
				"viewLevel"=>$row["view_level"]
			));
		}

		$json = json_encode($data);
		echo $json;
	}

	function getSpecifiedProgramList() {
		global $dbconn;

		$expertID = $_GET["expertID"];

		$query = "SELECT spp.*, bc.name as category_name FROM specified_prog_page spp, business_category bc WHERE spp.category_id = bc.id AND spp.expert_id = $expertID ORDER BY spp.id";

		$result = $dbconn->query($query);

		$data = array();

		while ($row = $result->fetch_assoc()) {
			array_push($data, array(
				"pageID"=>$row["id"],
				"programName"=>$row["program_name"],
				"focusProblemDesc"=>$row["focus_problem_desc"],
				"categoryName"=>$row["category_name"],
			));
		}

		$json = json_encode($data);
		echo $json;
	}

	function getGeneralProgramPage() {
		global $dbconn;

		$pageID = (int) $_GET["pageID"];

		$query = "SELECT gpp.*, bc.name as category_name, expert.name as expert_name FROM general_prog_page gpp, business_category bc, expert WHERE gpp.category_id = bc.id AND gpp.expert_id = expert.id AND gpp.id = $pageID";

		$result = $dbconn->query($query);

		$row = $result->fetch_assoc();

		$data = array(
			"pageID"=>$pageID,
			"expertID"=>$row["expert_id"],
			"categoryID"=>$row["category_id"],
			"programName"=>$row["program_name"],
			"expertName"=>$row["expert_name"],
			"categoryName"=>$row["category_name"],
			"focusProblemDesc"=>$row["focus_problem_desc"],
			"minCost"=>$row["min_cost"],
			"maxCost"=>$row["max_cost"],
			"programDuration"=>$row["program_duration"],
			"about"=>$row["about"],
			"expectedOutcome"=>$row["expected_outcome"],
			"viewLevel"=>$row["view_level"],
			"filePath"=>$row["proposal_path"]
		);

		$json = json_encode($data);
		echo $json;
	}

	function getSpecifiedProgramPage($internal = false, $specifiedPageID = null) {
		global $dbconn;

		$pageID = ($internal) ? (int) $specifiedPageID : (int) $_GET["pageID"];

		$query = "SELECT spp.*, bc.name as category_name, expert.name as expert_name, expert.email as expert_email FROM specified_prog_page spp, business_category bc, expert WHERE spp.category_id = bc.id AND spp.expert_id = expert.id AND spp.id = $pageID";

		$result = $dbconn->query($query);

		$row = $result->fetch_assoc();

		$data = array(
			"pageID"=>$pageID,
			"expertID"=>$row["expert_id"],
			"categoryID"=>$row["category_id"],
			"programName"=>$row["program_name"],
			"expertName"=>$row["expert_name"],
			"expertEmail"=>$row["expert_email"],
			"categoryName"=>$row["category_name"],
			"focusProblemDesc"=>$row["focus_problem_desc"],
			"cost"=>$row["cost"],
			"programDuration"=>$row["program_duration"],
			"about"=>$row["about"],
			"expectedOutcome"=>$row["expected_outcome"],
			"filePath"=>$row["proposal_path"]
		);

		// true if used for internal (same file) calls
		if ($internal)
			return $data;

		$json = json_encode($data);
		echo $json;
	}

	function getInterestedBusinessList() {
		global $dbconn;

		$programPageID = $_GET["programPageID"];

		$query = "SELECT * FROM interest_request WHERE general_prog_page_id = $programPageID";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";

			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"requestID"=>$row["ir_id"],
					"businessPageID"=>$row["business_page_id"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function checkGeneralProgramPageInAnyRequest() {
		global $dbconn;

		$pageID = $_GET["pageID"];

		// check in interest request
		$query = "
			SELECT general_prog_page_id
			FROM interest_request
			WHERE general_prog_page_id = $pageID
			GROUP BY general_prog_page_id
		";
		$result = $dbconn->query($query);
		$rowInterestRequest = $result->num_rows;
		// check in offer request
		$query = "
			SELECT general_prog_page_id
			FROM offer_request
			WHERE general_prog_page_id = $pageID
			GROUP BY general_prog_page_id
		";
		$result = $dbconn->query($query);
		$rowOfferRequest = $result->num_rows;

		if ($rowInterestRequest == 0 && $rowOfferRequest == 0)
			$data = array("status"=>"not found", "message"=>"this general program page doesn't have interest and offer request");
		else
			$data = array("status"=>"found", "message"=>"this general program page has interest or offer request");

		$json = json_encode($data);
		echo $json;
	}

	function checkSpecifiedProgramPageInAnyRequest() {
		global $dbconn;

		$pageID = (int) $_GET["pageID"];

		// check in interest request
		$query = "
		SELECT specified_prog_page_id
		FROM interest_request
		WHERE specified_prog_page_id = $pageID
		";
		$result = $dbconn->query($query);
		$rowInterestRequest = $result->num_rows;
		// check in offer request
		$query = "
		SELECT specified_prog_page_id
		FROM offer_request
		WHERE specified_prog_page_id = $pageID
		";
		$result = $dbconn->query($query);
		$rowOfferRequest = $result->num_rows;
		// check in mentor room
		$query = "
		SELECT specified_prog_page_id
		FROM mentor_room
		WHERE specified_prog_page_id = $pageID";
		$result = $dbconn->query($query);
		$rowMentorRoom = $result->num_rows;

		if ($rowInterestRequest == 0 && $rowOfferRequest == 0 && $rowMentorRoom == 0)
			$data = array("status"=>"not found", "message"=>"this specified program page doesn't have interest, offer request, and mentor room");
		else
			$data = array("status"=>"found", "message"=>"this specified program page has interest, offer request or mentor room");

		$json = json_encode($data);
		echo $json;
	}

	function getAvailableGeneralProgramPageListForBusinessPage() {
		global $dbconn;

		$expertID = (int) $_GET["expertID"];
		$businessPageID = (int) $_GET["businessPageID"];

		$query = "
		SELECT gpp.*, bc.name as category_name, IFNULL(rc.no_of_request, 0) as no_of_request
		FROM general_prog_page gpp 
		LEFT JOIN business_category bc 
		ON gpp.category_id = bc.id
		-- get the number of request a general program page has made to prevent more than 3 request sent
		LEFT JOIN (
			SELECT general_prog_page_id, COUNT(general_prog_page_id) as no_of_request
		    FROM offer_request
			GROUP BY general_prog_page_id
		) rc
		ON gpp.id = rc.general_prog_page_id
		WHERE IFNULL(rc.no_of_request, 0) < 3 AND gpp.expert_id = $expertID
		-- prevent request duplication
		AND gpp.id NOT IN (SELECT IFNULL(general_prog_page_id, -1) FROM offer_request WHERE business_page_id = $businessPageID)";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"generalPageID"=>$row["id"],
					"generalProgramName"=>$row["program_name"],
					"focusProblemDesc"=>$row["focus_problem_desc"],
					"categoryName"=>$row["category_name"],
					"viewLevel"=>$row["view_level"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
		/*
			if not found
			case 1: expert does not own any general program page
			case 2: all general program page already made 3 request which is the max number of request 1 program page could make
			case 3: all general program page already made a request to the particular program (LOL)
		*/

	}

	function getAvailableSpecifiedProgramListForBusinessPage() {
		global $dbconn;

		$expertID = (int) $_GET["expertID"];

		$query = "
		SELECT spp.*, bc.name as category_name 
		FROM specified_prog_page spp, business_category bc 
		WHERE spp.category_id = bc.id AND spp.expert_id = $expertID AND spp.id NOT IN (
		    SELECT IFNULL(specified_prog_page_id, -1) as specified_prog_page_id FROM offer_request
		) AND spp.id NOT IN (
		    SELECT IFNULL(specified_prog_page_id, -1) as specified_prog_page_id FROM interest_request
		) AND spp.id NOT IN (
			SELECT specified_prog_page_id FROM mentor_room
		)";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"pageID"=>$row["id"],
					"programName"=>$row["program_name"],
					"focusProblemDesc"=>$row["focus_problem_desc"],
					"categoryName"=>$row["category_name"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getOfferRequestList() {
		global $dbconn;

		$expertID = (int) $_GET["expertID"];

		$query = "
		SELECT ofr.*, gpp.program_name as gpp_program_name, spp.program_name as spp_program_name
		-- fetch all general and specified program page from a particular expert
		FROM
		(
		    SELECT ofr.* FROM offer_request ofr, specified_prog_page spp
		    WHERE ofr.specified_prog_page_id = spp.id AND spp.expert_id = $expertID
		    UNION
		    SELECT ofr.* FROM offer_request ofr, general_prog_page gpp
		    WHERE ofr.general_prog_page_id = gpp.id AND gpp.expert_id = $expertID
		) ofr LEFT JOIN general_prog_page gpp ON ofr.general_prog_page_id = gpp.id
		LEFT JOIN specified_prog_page spp ON ofr.specified_prog_page_id = spp.id
		ORDER BY ofr.or_id";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());

		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while ($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"requestID"=>$row["or_id"],
					"generalPageID"=>$row["general_prog_page_id"],
					"specifiedPageID"=>$row["specified_prog_page_id"],
					"businessPageID"=>$row["business_page_id"],
					"generalProgramName"=>$row["gpp_program_name"],
					"specifiedProgramName"=>$row["spp_program_name"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getExpertNotification() {
		global $dbconn;

		$expertID = (int) $_GET["expertID"];

		$query = "SELECT * FROM expert_notif WHERE expert_id = $expertID ORDER BY notif_id DESC";

		$result = $dbconn->query($query);

		$data = array("status"=>"not found", "list"=>array());
		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"notifID"=>$row["notif_id"],
					"notification"=>$row["notification"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	function getMentorRoomListForExpert() {
		global $dbconn;

		$expertID = (int) $_GET["expertID"];

		$query = "SELECT mr.*, spp.program_name FROM mentor_room mr, specified_prog_page spp WHERE mr.specified_prog_page_id = spp.id AND spp.expert_id = $expertID";

		$result = $dbconn->query($query);
		$data = array("status"=>"not found", "list"=>array());
		if ($result->num_rows > 0) {
			$data["status"] = "found";
			while($row = $result->fetch_assoc()) {
				array_push($data["list"], array(
					"roomID"=>$row["room_id"],
					"businessPageID"=>$row["business_page_id"],
					"specifiedPageID"=>$row["specified_prog_page_id"],
					"programName"=>$row["program_name"]
				));
			}
		}

		$json = json_encode($data);
		echo $json;
	}

	// POST
	// __ GENERAL
	function logout() {
		session_destroy();
	}
	
	// used by owner and expert to close or reject an interest request
	function deleteInterestRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];
		// delete all chat data
		$chatManager = new ChatManager($requestID, "dbconn.php", "ir");
		if ($chatManager->clearChatData()) {
			// delete request page
			$query = "DELETE FROM interest_request WHERE ir_id = $requestID";

			if ($dbconn->query($query))
				$data = array("status"=>"success", "message"=>"interest request deleted successfully");
			else 
				$data = array("status"=>"error", "message"=>$dbconn->error);
		}

		$json = json_encode($data);
		echo $json;
	}

	function deleteOfferRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];
		// delete all chat data
		$chatManager = new ChatManager($requestID, "dbconn.php", "or");
		if ($chatManager->clearChatData()) {
			// delete request page
			$query = "DELETE FROM offer_request WHERE or_id = $requestID";

			if ($dbconn->query($query))
				$data = array("status"=>"success", "message"=>"offer request deleted successfully");
			else
				$data = array("status"=>"error", "message"=>$dbconn->error);
		}

		$json = json_encode($data);
		echo $json;
	}

	// for mentor room resources page
	function addResource() {
		global $dbconn;

		$roomID = (int) $_POST["roomID"];
		$posterID = (int) $_POST["posterID"];
		$userType = $_POST["userType"];
		$title = $dbconn->real_escape_string($_POST["title"]);
		$message = $dbconn->real_escape_string($_POST["message"]);

		// get available space
		$query = "SELECT resources_space FROM mentor_room WHERE room_id = $roomID";
		$result = $dbconn->query($query);
		$row = $result->fetch_assoc();
		$availableSpace = (int) $row["resources_space"];

		if (isset($_FILES["file"])) {
			$file = $_FILES["file"];

			$uploadResponse = uploadResourceFile($file, $availableSpace);

			if ($uploadResponse["status"] == "error") {
				$json = json_encode($uploadResponse);
				exit($json);
			}

			// update the available space in mentor room
			$fileSize = (int) $file["size"];
			$updatedSpace = $availableSpace - $fileSize;
			$query = "UPDATE mentor_room SET resources_space = $updatedSpace WHERE room_id = $roomID";
			$dbconn->query($query);

			$filePath = "uploads/resources/" . $uploadResponse["fileName"];
			$tmp = explode(".", $file["name"]);
			$fileName = $dbconn->real_escape_string($tmp[0]);
		} else {
			$filePath = $fileName = null;
		}
		$createdDate = date("Y-m-d h:i");
		$query = "INSERT INTO resources VALUES (null, $roomID, $posterID, '$userType', '$title', '$message', '$filePath', '$createdDate', '$fileName')";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"resource added successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function deleteResource() {
		global $dbconn;

		$resourceID = (int) $_POST["resourceID"];
		$roomID = (int) $_POST["roomID"];
		$filePath = $_POST["filePath"];
		$fileSize = 0;

		// delete file if exist
		if ($filePath != "") {
			// update the available space in mentor room
			$filePath = "../" . $filePath;
			$fileSize = (int) filesize($filePath);
			$query = "UPDATE mentor_room SET resources_space = resources_space + $fileSize WHERE room_id = $roomID";
			$dbconn->query($query);

			// delete the file
			if (!unlink($filePath)) {
				$data = array("status"=>"error", "message"=>"An error occured: file is not found");
				$json = json_encode($data);
				exit($json);
			}
		}

		// delete from db
		$query = "DELETE FROM resources WHERE resource_id = $resourceID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"resource deleted successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;

	}

	// __ ADMIN
	function rejectTransactionFromInterestRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];

		// $query = "SELECT pot_path FROM interest_request WHERE ir_id = $requestID";

		// $result = $dbconn->query($query);

		// deleting previous uploaded file
		// if ($result->num_rows > 0) {
		// 	$row = $result->fetch_assoc();
		// 	$potPath = $row["pot_path"];

		// 	if ($potPath != null) {
		// 		$potPath = "../" . $potPath;
		// 		if (!unlink($potPath)) {
		// 			$data = array("status"=>"error", "message"=>"An error occured: file is not found");
		// 			$json = json_encode($data);
		// 			exit($json);
		// 		}
		// 	}
		// } else {
		// 	$data = array("status"=>"error", "message"=>"request page not found");
		// }

		$query = "UPDATE interest_request SET transaction_status = 'rejected' WHERE ir_id = $requestID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"transaction from interest request resolved successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function rejectTransactionFromOfferRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];

		$query = "UPDATE offer_request SET transaction_status = 'rejected' WHERE or_id = $requestID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"transaction from offer request rejected successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function resolveTransactionFromInterestRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];

		$query = "SELECT pot_path FROM interest_request WHERE ir_id = $requestID";

		$result = $dbconn->query($query);

		// deleting previous uploaded file
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$potPath = $row["pot_path"];

			if ($potPath != null) {
				$potPath = "../" . $potPath;
				if (!unlink($potPath)) {
					$data = array("status"=>"error", "message"=>"An error occured: file is not found");
					$json = json_encode($data);
					exit($json);
				}
			}
		} else {
			$data = array("status"=>"error", "message"=>"request page not found");
		}

		$query = "UPDATE interest_request SET pot_path = null, transaction_date = null, transaction_status = 'none' WHERE ir_id = $requestID";

		if ($dbconn->query($query)) 
			$data = array("status"=>"success", "message"=>"transaction from interest request rejected successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function resolveTransactionFromOfferRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];

		$query = "SELECT pot_path FROM offer_request WHERE or_id = $requestID";

		$result = $dbconn->query($query);

		// deleting previous uploaded file
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$potPath = $row["pot_path"];

			if ($potPath != null) {
				$potPath = "../" . $potPath;
				if (!unlink($potPath)) {
					$data = array("status"=>"error", "message"=>"An error occured: file is not found");
					$json = json_encode($data);
					exit($json);
				}
			}
		} else {
			$data = array("status"=>"error", "message"=>"request page not found");
		}

		$query = "UPDATE offer_request SET pot_path = null, transaction_date = null, transaction_status = 'none' WHERE or_id = $requestID";

		if ($dbconn->query($query)) 
			$data = array("status"=>"success", "message"=>"transaction from offer request resolved successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function createMentorRoom() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];
		$requestType = $_POST["requestType"];

		// copy needed data from initial request page
		if ($requestType == "ir")
			$query = "SELECT * FROM interest_request WHERE ir_id = $requestID";
		else
			$query = "SELECT * FROM offer_request WHERE or_id = $requestID";

		$result = $dbconn->query($query);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$businessPageID = (int) $row["business_page_id"];
			$specifiedPageID = (int) $row["specified_prog_page_id"];
			$createdDate = date("y-m-d h:i");
			$potPath = $row["pot_path"];
			$transactionDate = $row["transaction_date"];

			// create new mentor room
			$query = "INSERT INTO mentor_room VALUES (null, $businessPageID, $specifiedPageID, '$createdDate', '$potPath', '$transactionDate', 1000000000)";

			if ($dbconn->query($query)) {
				// create notification
				$businessPageData = getBusinessPage(true, $businessPageID);
				$programPageData = getSpecifiedProgramPage(true, $specifiedPageID);

				$ownerID = (int) $businessPageData["ownerID"];
				$expertID = (int) $programPageData["expertID"];

				$businessName = $businessPageData["businessName"];
				$programName = $programPageData["programName"];
				// for owner
				$query = "INSERT INTO owner_notif VALUES (null, $ownerID, 'Mentor Room has been created for business page: $businessName with expert program: $programName. The room can be accessed through the Mentor Room tab.')";
				$dbconn->query($query);

				// for expert
				$query = "INSERT INTO expert_notif VALUES (null, $expertID, 'Mentor Room has been created for expert program: $programName with business page: $businessName. The room can be accessed through the Mentor Room tab.')";
				$dbconn->query($query);

				// delete initial request
				if ($requestType == "ir")
					deleteInterestRequest();
				else
					deleteOfferRequest();
			}		
		} else {
			$data = array("status"=>"error", "message"=>"request page not found");
			$json = json_encode($data);
			echo $json;
		}
	}

	// __ OWNER
	function updateOwner() {
		global $dbconn;
		
		$ownerID = $_POST["ownerID"];
		$cityID = $_POST["cityID"];
		$ownerName = $_POST["ownerName"];
		$ownerEmail = $_POST["ownerEmail"];
		$ownerPassword = $_POST["ownerPassword"];

		$queryEmailCheck = "SELECT id, email FROM business_owner WHERE email = '$ownerEmail'";

		$result = $dbconn->query($queryEmailCheck);

		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();

			// true, if the email is already registered by someone else
			if ($ownerID != (int) $row["id"]) {
				$data = array("status"=>"error", "message"=>"Email is already registered");

				$json = json_encode($data);
				exit($json);
			}
		}

		$query = "UPDATE business_owner SET city_id = $cityID, name = '$ownerName', email = '$ownerEmail', password = '$ownerPassword' WHERE id = $ownerID";
		
		$dbconn->query($query);

		$data = array("status"=>"success", "message"=>"Profile updated successfully");
		
		$json = json_encode($data);
		echo $json;
	}

	function updateBusinessPage() {
		global $dbconn;

		$pageID = $_POST["pageID"];
		$categoryID = $_POST["categoryID"];
		$cityID = $_POST["cityID"];
		$businessName = $_POST["businessName"];
		$shortProblemDesc = $_POST["shortProblemDesc"];
		$longProblemDesc = $_POST["longProblemDesc"];
		$about = $_POST["about"];
		$initFilePath = $_POST["initFilePath"];
		$viewLevel = $_POST["viewLevel"];

		if (isset($_FILES["file"])) {
			$file = $_FILES["file"];

			// upload the new file
			$dir = "../uploads/business_proposal/";

			$uploadResponse = uploadFile($file, $dir);

			// upload file error
			if ($uploadResponse["status"] == "error") {
				$json = json_encode($uploadResponse);
				exit($json);
			}

			$filePath = "uploads/business_proposal/" . $uploadResponse["fileName"];

		} else {
			$filePath = $initFilePath;
		}

		$query = "UPDATE business_page SET category_id = $categoryID, city_id = $cityID, business_name = '$businessName', short_problem_desc = '$shortProblemDesc', long_problem_desc = '$longProblemDesc', about = '$about', proposal_path = '$filePath', view_level = '$viewLevel' WHERE id = $pageID";

		$dbconn->query($query);

		$data = array("status"=>"success", "message"=>"Page updated successfully");

		$json = json_encode($data);
		echo $json;
	}

	function createBusinessPage() {
		global $dbconn;

		$ownerID = $_POST["ownerID"];
		$categoryID = $_POST["categoryID"];
		$cityID = $_POST["cityID"];
		$businessName = $_POST["businessName"];
		$shortProblemDesc = $_POST["shortProblemDesc"];
		$longProblemDesc = $_POST["longProblemDesc"];
		$about = $_POST["about"];
		$viewLevel = $_POST["viewLevel"];

		// if owner attach file
		if (isset($_FILES["file"])) {
			$file = $_FILES["file"];
			$dir = "../uploads/business_proposal/";

			$uploadResponse = uploadFile($file, $dir);

			// upload file error
			if ($uploadResponse["status"] == "error") {
				$json = json_encode($uploadResponse);
				exit($json);
			}

			$filePath = "uploads/business_proposal/" . $uploadResponse["fileName"];
		} else {
			$filePath = "";
		}

		$query = "INSERT INTO business_page VALUES (null, $ownerID, $categoryID, $cityID, '', '$businessName', '$shortProblemDesc', '$longProblemDesc', '$about', '$filePath', '$viewLevel')";

		$dbconn->query($query);

		$data = array("status"=>"success", "message"=>"Page created successfully");

		$json = json_encode($data);
		echo $json;
	}

	function deleteBusinessPage() {
		global $dbconn;

		$pageID = $_POST["pageID"];

		$filePathQuery = "SELECT proposal_path FROM business_page WHERE id = $pageID";

		$result = $dbconn->query($filePathQuery);

		$row = $result->fetch_assoc();

		// delete the file from dir
		if ($row["proposal_path"] != "") {
			$filePath = "../" . $row["proposal_path"];

			if (!unlink($filePath)) {
				$data = array("status"=>"error", "message"=>"An error occured: file is not found");
				$json = json_encode($data);
				exit($json);
			}
		}

		$query = "DELETE FROM business_page WHERE id = $pageID";

		$dbconn->query($query);

		$data = array("status"=>"success", "message"=>"Page deleted successfully");

		$json = json_encode($data);
		echo $json;
	}

	function deleteBusinessProposalFile() {
		global $dbconn;

		$pageID = $_POST["pageID"];
		$filePath = "../" . $_POST["filePath"];

		if (!unlink($filePath)) {
			$data = array("status"=>"error", "message"=>"An error occured: file is not found");
			$json = json_encode($data);
			exit($json);
		}

		$query = "UPDATE business_page SET proposal_path = '' WHERE id = $pageID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"File deleted successfully");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function createInterestRequest() {
		global $dbconn;

		$businessPageID = $_POST["businessPageID"];
		$programPageID = $_POST["programPageID"];

		$query = "INSERT INTO interest_request (ir_id, business_page_id, general_prog_page_id, specified_prog_page_id) VALUES (null, $businessPageID, $programPageID, null)";


		if ($dbconn->query($query)) {
			$requestID = $dbconn->insert_id;
			$data = array("status"=>"success", "requestID"=>$requestID);
		} else {
			$data = array("status"=>"success", "message"=>$dbconn->error);
		}

		$json = json_encode($data);
		echo $json;

	}

	function uploadPOT() {
		global $dbconn;

		$req = $_POST["req"];
		$requestID = (int) $_POST["requestID"];

		if (isset($_FILES["file"])) {
			$file = $_FILES["file"];
			$dir = "../uploads/POT/";

			$uploadResponse = uploadImage($file, $dir);

			// upload file error
			if ($uploadResponse["status"] == "error") {
				$json = json_encode($uploadResponse);
				exit($json);
			}

			$filePath = "uploads/POT/" . $uploadResponse["fileName"];
			$transactionDate = date("y-m-d h:i");
			if ($req == "ir") 
				$query = "UPDATE interest_request SET pot_path = '$filePath', transaction_date = '$transactionDate', transaction_status = 'pending' WHERE ir_id = $requestID";
			else
				$query = "UPDATE offer_request SET pot_path = '$filePath', transaction_date = '$transactionDate', transaction_status = 'pending' WHERE or_id = $requestID";

			if ($dbconn->query($query))
				$data = array("status"=>"success", "message"=>"POT uploaded successfully, waiting for admin verification");
			else
				$data = array("status"=>"error", "message"=>$dbconn->error);
		} else {
			$data = array("status"=>"error", "message"=>"can't upload: POT file missing");
		}

		$json = json_encode($data);
		echo $json;
	}

	function deleteOwnerNotification() {
		global $dbconn;

		$ownerID = (int) $_POST["ownerID"];
		$notifID = (int) $_POST["notifID"];

		$query = "DELETE FROM owner_notif WHERE notif_id = $notifID AND owner_id = $ownerID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"notification id: {$notifID} deleted successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function closeMentorRoom() {
		global $dbconn;

		$roomID = (int) $_POST["roomID"];

		// delete all resources
		$query = "SELECT * FROM resources WHERE room_id = $roomID";
		$result = $dbconn->query($query);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				if ($row["file_path"] != "") {
					$filePath = "../" . $row["file_path"];

					if (!unlink($filePath)) {
						$data = array("status"=>"error", "message"=>"An error occured: file is not found");
						$json = json_encode($data);
						exit($json);
					}
				}

				// delete resource data in db
				$resourceID = (int) $row["resource_id"];
				$query = "DELETE FROM resources WHERE resource_id = $resourceID";
				$dbconn->query($query);
			}
		}

		// delete mentor room chatdata
		$chatManager = new ChatManager(null, "dbconn.php", "mr", $roomID);
		if ($chatManager->clearChatData()) {
			// delete POT file
			$query = "SELECT pot_path FROM mentor_room WHERE room_id = $roomID";
			$result = $dbconn->query($query);
			$row = $result->fetch_assoc();
			$filePath = "../" . $row["pot_path"];
			if (!unlink($filePath)) {
				$data = array("status"=>"error", "message"=>"An error occured: file is not found");
				$json = json_encode($data);
				exit($json);
			}

			// create notification
			$query = "SELECT * FROM mentor_room WHERE room_id = $roomID";
			$result = $dbconn->query($query);
			$row = $result->fetch_assoc();
			$businessPageID = (int) $row["business_page_id"];
			$specifiedPageID = (int) $row["specified_prog_page_id"];

			$businessPageData = getBusinessPage(true, $businessPageID);
			$programPageData = getSpecifiedProgramPage(true, $specifiedPageID);

			$ownerID = (int) $businessPageData["ownerID"];
			$expertID = (int) $programPageData["expertID"];

			$businessName = $businessPageData["businessName"];
			$programName = $programPageData["programName"];

			// for owner
			$query = "INSERT INTO owner_notif VALUES (null, $ownerID, 'Mentor Room for business page: $businessName with expert program: $programName has been closed.')";
			$dbconn->query($query);

			// for expert
			$query = "INSERT INTO expert_notif VALUES (null, $expertID, 'Mentor Room for expert program: $programName with business page: $businessName has been closed.')";
			$dbconn->query($query);

			// delete mentor room data in db
			$query = "DELETE FROM mentor_room WHERE room_id = $roomID";
			if ($dbconn->query($query))
				$data = array("status"=>"success", "message"=>"mentor room has been closed successfully");
			else
				$data = array("status"=>"error", "message"=>$dbconn->error);

			$json = json_encode($data);
			echo $json;
		}

	}

	// __ EXPERT
	function createProgramPage() {
		// this function is used for both general and specified programs
		global $dbconn;

		$programType = $_POST["programType"];
		$expertID = $_POST["expertID"];
		$categoryID = $_POST["categoryID"];
		$programName = $_POST["programName"];
		$programDuration = $_POST["programDuration"];
		$focusProblemDesc = $_POST["focusProblemDesc"];
		$about = $_POST["about"];
		$expectedOutcome = $_POST["expectedOutcome"];

		// different value based on program type
		$minCost = $_POST["minCost"];
		$maxCost = $_POST["maxCost"];
		$cost = $_POST["cost"];
		$viewLevel = $_POST["viewLevel"];

		if (isset($_FILES["file"])) {
			$file = $_FILES["file"];

			if ($programType == "general")
				$dir = "uploads/mentoring_proposal/general/";
			else 
				$dir = "uploads/mentoring_proposal/specified/";

			$uploadResponse = uploadFile($file, "../" . $dir);

			// upload file error
			if ($uploadResponse["status"] == "error") {
				$json = json_encode($uploadResponse);
				exit($json);
			}

			$filePath = $dir . $uploadResponse["fileName"];

		} else {
			$filePath = "";
		}

		if ($programType == "general")
			$query = "INSERT INTO general_prog_page VALUES (null, $expertID, $categoryID, '$programName', '$focusProblemDesc', $minCost, $maxCost, $programDuration, '$about', '$expectedOutcome', '$filePath', '$viewLevel')";
		else
			$query = "INSERT INTO specified_prog_page VALUES (null, $expertID, $categoryID, '$programName', '$focusProblemDesc', $cost, $programDuration, '$about', '$expectedOutcome', '$filePath')";

		if ($dbconn->query($query)) 
			$data = array("status"=>"success", "message"=>"Page created successfully");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function updateGeneralProgramPage() {
		global $dbconn;

		$pageID = $_POST["pageID"];
		$categoryID = $_POST["categoryID"];
		$programName = $_POST["programName"];
		$minCost = $_POST["minCost"];
		$maxCost = $_POST["maxCost"];
		$programDuration = $_POST["programDuration"];
		$focusProblemDesc = $_POST["focusProblemDesc"];
		$about = $_POST["about"];
		$expectedOutcome = $_POST["expectedOutcome"];
		$initFilePath = $_POST["initFilePath"];
		$viewLevel = $_POST["viewLevel"];

		if (isset($_FILES["file"])) {
			$file = $_FILES["file"];
			
			// upload the new file
			$dir = "uploads/mentoring_proposal/general/";

			$uploadResponse = uploadFile($file, "../" . $dir);

			// upload file error
			if ($uploadResponse["status"] == "error") {
				$json = json_encode($uploadResponse);
				exit($json);
			}

			$filePath = $dir . $uploadResponse["fileName"];

		} else {
			$filePath = $initFilePath;
		}

		$query = "UPDATE general_prog_page SET category_id = $categoryID, program_name = '$programName', focus_problem_desc = '$focusProblemDesc', min_cost = $minCost, max_cost = $maxCost, program_duration = $programDuration, about = '$about', expected_outcome = '$expectedOutcome', proposal_path = '$filePath', view_level = '$viewLevel' WHERE id = $pageID";
	
		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"Page updated successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function updateSpecifiedProgramPage() {
		global $dbconn;

		$pageID = $_POST["pageID"];
		$categoryID = $_POST["categoryID"];
		$programName = $_POST["programName"];
		$cost = $_POST["cost"];
		$programDuration = $_POST["programDuration"];
		$focusProblemDesc = $_POST["focusProblemDesc"];
		$about = $_POST["about"];
		$expectedOutcome = $_POST["expectedOutcome"];
		$initFilePath = $_POST["initFilePath"];

		if (isset($_FILES["file"])) {
			$file = $_FILES["file"];

			// upload the new file
			$dir = "uploads/mentoring_proposal/specified/";

			$uploadResponse = uploadFile($file, "../" . $dir);

			// upload file error
			if ($uploadResponse["status"] == "error") {
				$json = json_encode($uploadResponse);
				exit($json);
			}

			$filePath = $dir . $uploadResponse["fileName"];

		} else {
			$filePath = $initFilePath;
		}

		$query = "UPDATE specified_prog_page SET category_id = $categoryID, program_name = '$programName', focus_problem_desc = '$focusProblemDesc', cost = $cost, program_duration = $programDuration, about = '$about', expected_outcome = '$expectedOutcome', proposal_path = '$filePath' WHERE id = $pageID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"Page updated successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function deleteGeneralProgramPage() {
		global $dbconn;

		$pageID = $_POST["pageID"];

		$filePathQuery = "SELECT proposal_path FROM general_prog_page WHERE id = $pageID";

		$result = $dbconn->query($filePathQuery);

		$row = $result->fetch_assoc();

		// delete the file from dir
		if ($row["proposal_path"] != "") {
			$filePath = "../" . $row["proposal_path"];

			if (!unlink($filePath)) {
				$data = array("status"=>"error", "message"=>"An error occured: file is not found");
				$json = json_encode($data);
				exit($json);
			}
		}

		$query = "DELETE FROM general_prog_page WHERE id = $pageID";

		if($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"Page deleted successfully");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function deleteSpecifiedProgramPage() {
		global $dbconn;

		$pageID = $_POST["pageID"];

		$filePathQuery = "SELECT proposal_path FROM specified_prog_page WHERE id = $pageID";

		$result = $dbconn->query($filePathQuery);

		$row = $result->fetch_assoc();

		// delete the file from dir
		if ($row["proposal_path"] != "") {
			$filePath = "../" . $row["proposal_path"];

			if (!unlink($filePath)) {
				$data = array("status"=>"error", "message"=>"An error occured: file is not found");
				$json = json_encode($data);
				exit($json);
			}
		}

		$query = "DELETE FROM specified_prog_page WHERE id = $pageID";

		if($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"Page deleted successfully");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function deleteGeneralMentoringProposalFile() {
		global $dbconn;

		$pageID = $_POST["pageID"];
		$filePath = "../" . $_POST["filePath"];

		if (!unlink($filePath)) {
			$data = array("status"=>"error", "message"=>"An error occured: file is not found");
			$json = json_encode($data);
			exit($json);
		}

		$query = "UPDATE general_prog_page SET proposal_path = '' WHERE id = $pageID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"File deleted successfully");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function deleteSpecifiedMentoringProposalFile() {
		global $dbconn;

		$pageID = $_POST["pageID"];
		$filePath = "../" . $_POST["filePath"];

		if (!unlink($filePath)) {
			$data = array("status"=>"error", "message"=>"An error occured: file is not found");
			$json = json_encode($data);
			exit($json);
		}

		$query = "UPDATE specified_prog_page SET proposal_path = '' WHERE id = $pageID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"File deleted successfully");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function setSpecifiedProgramPageOnInterestRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];
		$specifiedPageID = (int) $_POST["specifiedPageID"];

		$query = "UPDATE interest_request SET specified_prog_page_id = $specifiedPageID WHERE ir_id = $requestID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"specified program page has successfully set to interest request page");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function setSpecifiedProgramPageOnOfferRequest() {
		global $dbconn;

		$requestID = (int) $_POST["requestID"];
		$specifiedPageID = (int) $_POST["specifiedPageID"];

		$query = "UPDATE offer_request SET specified_prog_page_id = $specifiedPageID WHERE or_id = $requestID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"specified program page has successfully set to offer request page");
		else 
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	function createOfferRequest() {
		global $dbconn;

		$programPageType = $_POST["programPageType"];
		$programPageID = (int) $_POST["programPageID"];
		$businessPageID = (int) $_POST["businessPageID"];

		$query = "";
		if ($programPageType == "specified")
			$query = "INSERT INTO offer_request (or_id, general_prog_page_id, specified_prog_page_id, business_page_id) VALUES (null, null, $programPageID, $businessPageID)";
		else
			$query = "INSERT INTO offer_request (or_id, general_prog_page_id, specified_prog_page_id, business_page_id) VALUES (null, $programPageID, null, $businessPageID)";

		if ($dbconn->query($query)) {
			$requestID = $dbconn->insert_id;
			$data = array("status"=>"success", "requestID"=>$requestID);
		} 
		else {
			$data = array("status"=>"error", "message"=>$dbconn->error);
		}

		$json = json_encode($data);
		echo $json;
	}

	function deleteExpertNotification() {
		global $dbconn;

		$expertID = (int) $_POST["expertID"];
		$notifID = (int) $_POST["notifID"];

		$query = "DELETE FROM expert_notif WHERE notif_id = $notifID AND expert_id = $expertID";

		if ($dbconn->query($query))
			$data = array("status"=>"success", "message"=>"notification id: {$notifID} deleted successfully");
		else
			$data = array("status"=>"error", "message"=>$dbconn->error);

		$json = json_encode($data);
		echo $json;
	}

	if ($_SERVER["REQUEST_METHOD"] == "GET") {

		switch ($_GET["functionCall"]) {
			case "getOwnerInfo":
				getOwnerInfo();
				break;
			case "getProvinceList":
				getProvinceList();
				break;
			case "getCityList":
				getCityList();
				break;
			case "getBusinessPageList":
				getBusinessPageList();
				break;
			case "getBusinessPage":
				getBusinessPage();
				break;
			case "getCategoryList":
				getCategoryList();
				break;
			case "getGeneralProgramList":
				getGeneralProgramList();
				break;
			case "getSpecifiedProgramList":
				getSpecifiedProgramList();
				break;
			case "getGeneralProgramPage":
				getGeneralProgramPage();
				break;
			case "getSpecifiedProgramPage":
				getSpecifiedProgramPage();
				break;
			case "getInterestRequestPage":
				getInterestRequestPage();
				break;
			case "getInterestRequestList":
				getInterestRequestList();
				break;
			case "getInterestedBusinessList":
				getInterestedBusinessList();
				break;
			case "getAvailableBusinessPageListForProgramPage":
				getAvailableBusinessPageListForProgramPage();
				break;
			case "checkBusinessPageInAnyRequest":
				checkBusinessPageInAnyRequest();
				break;
			case "checkGeneralProgramPageInAnyRequest":
				checkGeneralProgramPageInAnyRequest();
				break;
			case "getAvailableSpecifiedProgramListForBusinessPage":
				getAvailableSpecifiedProgramListForBusinessPage();
				break;
			case "checkSpecifiedProgramPageInAnyRequest":
				checkSpecifiedProgramPageInAnyRequest();
				break;
			case "checkSpecifiedProgramPageAvailabilityForInterestRequest":
				checkSpecifiedProgramPageAvailabilityForInterestRequest();
				break;
			case "getAvailableGeneralProgramPageListForBusinessPage":
				getAvailableGeneralProgramPageListForBusinessPage();
				break;
			case "getOfferRequestPage":
				getOfferRequestPage();
				break;
			case "getOfferRequestList":
				getOfferRequestList();
				break;
			case "getMentoringOfferList":
				getMentoringOfferList();
				break;
			case "checkSpecifiedProgramPageAvailabilityForOfferRequest":
				checkSpecifiedProgramPageAvailabilityForOfferRequest();
				break;
			case "getPendingTransactionFromInterestRequest":
				getPendingTransactionFromInterestRequest();
				break;
			case "getPendingTransactionFromOfferRequest":
				getPendingTransactionFromOfferRequest();
				break;
			case "getRejectedTransactionFromInterestRequest":
				getRejectedTransactionFromInterestRequest();
				break;
			case "getRejectedTransactionFromOfferRequest":
				getRejectedTransactionFromOfferRequest();
				break;
			case "getOwnerNotification":
				getOwnerNotification();
				break;
			case "getExpertNotification":
				getExpertNotification();
				break;
			case "getMentorRoomListForOwner":
				getMentorRoomListForOwner();
				break;
			case "getMentorRoomListForExpert":
				getMentorRoomListForExpert();
				break;
			case "getMentorRoom":
				getMentorRoom();
				break;
			case "getResources":
				getResources();
				break;
		}

	} else {

		switch ($_POST["functionCall"]) {
			case "updateOwner":
				updateOwner();
				break;
			case "updateBusinessPage":
				updateBusinessPage();
				break;
			case "createBusinessPage":
				createBusinessPage();
				break;
			case "deleteBusinessPage":
				deleteBusinessPage();
				break;
			case "logout":
				logout();
				break;
			case "deleteGeneralProgramPage":
				deleteGeneralProgramPage();
				break;
			case "deleteSpecifiedProgramPage":
				deleteSpecifiedProgramPage();
				break;
			case "createProgramPage":
				createProgramPage();
				break;
			case "updateGeneralProgramPage":
				updateGeneralProgramPage();
				break;
			case "updateSpecifiedProgramPage":
				updateSpecifiedProgramPage();
				break;
			case "deleteBusinessProposalFile":
				deleteBusinessProposalFile();
				break;
			case "deleteGeneralMentoringProposalFile":
				deleteGeneralMentoringProposalFile();
				break;
			case "deleteSpecifiedMentoringProposalFile":
				deleteSpecifiedMentoringProposalFile();
				break;
			case "createInterestRequest":
				createInterestRequest();
				break;
			case "deleteInterestRequest":
				deleteInterestRequest();
				break;
			case "setSpecifiedProgramPageOnInterestRequest";
				setSpecifiedProgramPageOnInterestRequest();
				break;
			case "createOfferRequest":
				createOfferRequest();
				break;
			case "setSpecifiedProgramPageOnOfferRequest":
				setSpecifiedProgramPageOnOfferRequest();
				break;
			case "deleteOfferRequest":
				deleteOfferRequest();
				break;
			case "uploadPOT":
				uploadPOT();
				break;
			case "rejectTransactionFromInterestRequest":
				rejectTransactionFromInterestRequest();
				break;
			case "rejectTransactionFromOfferRequest":
				rejectTransactionFromOfferRequest();
				break;
			case "resolveTransactionFromInterestRequest":
				resolveTransactionFromInterestRequest();
				break;
			case "resolveTransactionFromOfferRequest":
				resolveTransactionFromOfferRequest();
				break;
			case "createMentorRoom":
				createMentorRoom();
				break;
			case "deleteOwnerNotification":
				deleteOwnerNotification();
				break;
			case "deleteExpertNotification":
				deleteExpertNotification();
				break;
			case "addResource":
				addResource();
				break;
			case "deleteResource":
				deleteResource();
				break;
			case "closeMentorRoom":
				closeMentorRoom();
				break;
		}
	}

	$dbconn->close();
 ?>