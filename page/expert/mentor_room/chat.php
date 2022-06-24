<?php 
	session_start();
	if (!isset($_SESSION["expertID"])) 
		exit("An error occured: user not defined. <a href='../../login.html'>Click here to login</a>");
	if (!isset($_SESSION["roomID"]))
		exit("An error occured: page not defined.");

	$expertID = $_SESSION["expertID"];
	$roomID = $_SESSION["roomID"];

	require "../../../ws/db/ChatManager.php";
	$chatManager = new ChatManager(null, "../../../utility/dbconn.php", "mr", $roomID);
	$row = $chatManager->who();
	$chatdata = $chatManager->loadChatData();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../../css/general/general.css?v=<?php echo time() ?>">
	<link rel="stylesheet" href="../../../css/general/chat.css?v=<?php echo time() ?>">
	<title>Mentor Room: chat</title>
	<link rel="stylesheet" href="../../../css/general/general.css?v=<?php echo time() ?>">
	<link rel="stylesheet" href="../../../css/general/owner/mr_navbar.css">
	<link rel="stylesheet" href="../../../css/general/chat.css?v=<?php echo time() ?>">
	<link rel="stylesheet" href="../../../css/expert/mentor_room/chat.css">
</head>
<body data-room-id="<?php echo $roomID ?>" data-sender-info="expert-<?php echo $row['expert_id'].'-'.$row['expert_name'] ?>" data-recepient-info="<?php echo $row['owner_id'] ?>" data-scope="mr">
	<header>
		<nav>
			<div class="logo-wrapper"><p class="logo">LOGO</p><span class="mr-label">Mentor Room</span></div>
			<a href="home.php?<?php echo 'roomID='.$roomID ?>">Home</a>
			<a href="#">Chat</a>
			<a href="resources.php">Resources</a>
			<button id="exit-btn">Exit</button>
		</nav>
	</header>
	<main>
		<section id="chat">
			<h2>Chat</h2>
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
		</section>
	</main>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="../../../js/chat_mr.js?v=<?php echo time() ?>"></script>
	<script src="../../../js/expert/mentor_room/chat.js?v=<?php echo time() ?>"></script>
</body>
</html>