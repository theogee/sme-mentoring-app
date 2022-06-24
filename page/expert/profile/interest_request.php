<?php 
	session_start();
	if (!isset($_SESSION["expertID"])) 
		exit("An error occured: user not defined. <a href='../../login.html'>Click here to login</a>");

	$_SESSION["requestID"] = $requestID = $_GET["requestID"];

	require "../../../ws/db/ChatManager.php";
	$chatManager = new ChatManager($requestID, "../../../utility/dbconn.php", "ir");
	$row = $chatManager->who();
	$chatdata = $chatManager->loadChatData();
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Interest Request</title>
	<link rel="stylesheet" href="../../../css/general/general.css?v=<?php echo time() ?>">
	<link rel="stylesheet" href="../../../css/general/owner/navbar.css">
	<link rel="stylesheet" href="../../../css/general/chat.css?v=<?php echo time() ?>">
	<link rel="stylesheet" href="../../../css/expert/profile/interest_request.css?v=<?php echo time() ?>">
</head>
<body data-request-id="<?php echo $requestID ?>" data-sender-info="expert-<?php echo $row['expert_id'].'-'.$row['expert_name'] ?>" data-recepient-info="<?php echo $row['owner_id'] ?>" data-scope="ir">
	<header>
		<nav>
			<div class="logo-wrapper" style="display: inline-block">LOGO</div>
			<div class="middle-column">
				<a href="../search/searchasmentor.php">Find Business Problem</a>
				<a href="create_page.php">Create Mentoring Program</a>
			</div>
			<div class="right-column">
				<a href="profile.php"><img class="profile-icon" src="../../../assets/icons/profile.svg" alt="profile"></a>
				<button id="logout-btn"><img class="logout-icon" src="../../../assets/icons/logout.svg" alt="logout"></button>
			</div>
		</nav>
	</header>
	<main>
		<section id="notification">
			<div id="ajax-notif"></div>
		</section>

		<section id="request-info">
			<div id="ajax-request-header"></div>
			<h2>From:</h2>
			<div id="ajax-business-page"></div>
			<h2>To:</h2>
			<div id="ajax-general-page"></div>
			<h2>Specified Program:</h2>
			<!-- to contain specified page info if exist -->
			<div id="ajax-specified-page"></div>
			<button id="select-btn" style="display:none">Select specified program</button>
			<!-- to contain specified page list if expert not yet attach one -->
			<div id="ajax-specified-page-list" style="display:none">
				<p class="select-text">Select specified program page</p>
				<button id="cancel-btn">X</button>
				<div class="container-wrapper">
					<div id="ajax-container"></div>
				</div>
			</div>
		</section>
		<section id="request-discussion">
			<h2>Discussion</h2>
			<div id="live-chat">
				<div id="chat-box">
					<div id="begin-msg">
						<span>This is the begining of your chat</span>
					</div>
					<?php 
						while ($data = $chatdata->fetch_assoc()) {
							if ($data["sender_id"] == $row["expert_id"]) {
								echo '
								<div class="self chat-container">
									<div class="chat-info">
										<span>You</span>
									</div>
									<div class="self chat-bubble">
										<span class="chat-dt">'.date("d/m/y h:i A", strtotime($data["created_on"])).'</span>
										<span class="chat-msg">'.$data["msg"].'</span>
									</div>
								</div>';
							} else {
								echo '
								<div class="chat-container">
									<div class="chat-info">
										<span>'.$data["name"].'</span>
									</div>
									<div class="chat-bubble">
										<span class="chat-msg">'.$data["msg"].'</span>
										<span class="chat-dt">'.date("d/m/y h:i A", strtotime($data["created_on"])).'</span>
									</div>
								</div>';
							}
						}
					 ?>
				</div>
				<div id="chat-interface">
					<span class="textarea" role="textbox" contenteditable id="text-box" maxlength="500"></span><br>
					<button id="send-btn">send</button>
				</div>
			</div>
			<button id="reject-btn">Reject Request</button>
		</section>
		<br>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../../../js/expert/profile/interest_request.js?v=<?php echo time(); ?>"></script>
    <script src="../../../js/chat.js?v=<?php echo time(); ?>"></script>
</body>
</html>