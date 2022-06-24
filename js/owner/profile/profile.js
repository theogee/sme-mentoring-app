$(document).ready(() => {
	const businessPageLink = $("#business-page-link");
	const interestRequestLink = $("#interest-request-link");
	const mentorRoomLink = $("#mentor-room-link");

	getOwnerInfo();
	getBusinessPageList();
	getOwnerNotification();

	businessPageLink.on("click", () => {
		if (!businessPageLink.hasClass("selected-link")) {
			getBusinessPageList();
			businessPageLink.addClass("selected-link");
			interestRequestLink.removeClass("selected-link");
			mentorRoomLink.removeClass("selected-link");
		}
	});

	interestRequestLink.on("click", () => {
		if (!interestRequestLink.hasClass("selected-link")) {
			getInterestRequestList();
			interestRequestLink.addClass("selected-link");
			businessPageLink.removeClass("selected-link");
			mentorRoomLink.removeClass("selected-link");
		}
	});

	mentorRoomLink.on("click", () => {
		if (!mentorRoomLink.hasClass("selected-link")) {
			getMentorRoomListForOwner();
			mentorRoomLink.addClass("selected-link");
			businessPageLink.removeClass("selected-link");
			interestRequestLink.removeClass("selected-link");
		}
	});



	$("#ajax-notif").on("click", ".notif-card > .close-notif-btn", e => {
		const notifID = e.currentTarget.getAttribute("data-notif-id");
		deleteOwnerNotification(notifID);
	});

	$("#logout-btn").on("click", logout);
});



function deleteOwnerNotification(notifID) {
	const ownerID = $("#ajax-owner-name").attr("data-owner-id");
	$.post("../../../utility/functions.php", {
		functionCall: "deleteOwnerNotification",
		ownerID: ownerID,
		notifID: notifID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			getOwnerNotification();
		else
			alert(response.message); 
	});
}

function getOwnerNotification() {
	const ownerID = $("#ajax-owner-name").attr("data-owner-id");
	$.get("../../../utility/functions.php", {
		functionCall: "getOwnerNotification",
		ownerID: ownerID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		let content = "";
		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<div class='notif-card'>
					<button class="close-notif-btn" data-notif-id='${response.list[i].notifID}'>X</button>	
					<p>${response.list[i].notification}</p>
				</div>`;
			}

			$("#ajax-notif").html(content);
		} else 
			$("#ajax-notif").html("");
	});
}

function getMentorRoomListForOwner() {
	const ownerID = $("#ajax-owner-name").attr("data-owner-id");

	$("#ajax-container").html("");

	$.get("../../../utility/functions.php", {
		functionCall: "getMentorRoomListForOwner",
		ownerID: ownerID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "found") {
			for (let i in response.list) {
				getMentorRoomCard(response.list[i].specifiedPageID, response.list[i].roomID, response.list[i].businessName);
			}
		} else {
			$("#ajax-container").html("<p class='mr-list-notif'>No mentor room establish</p>");
		}
	});
}

function getMentorRoomCard(specifiedPageID, roomID, businessName) {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: specifiedPageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		const content = `
		<div class='mentor-room-card'>
			<p class="mentor-room-card-title">
				Business: ${businessName}<br>
				Program: ${response.programName} <span class="mentor-room-card-category">${response.categoryName}</span> <span class="specified-tag">specified</specified>
			</p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Mentored By: ${response.expertName}</p>
			<a class="mentor-room-link" href='../mentor_room/home.php?roomID=${roomID}'>Mentor Room</a>
		</div>`;

		$("#ajax-container").append(content);
	});
}

function getInterestRequestList() {
	const ownerID = parseInt($("#ajax-owner-name").attr("data-owner-id"));

	$("#ajax-container").html("");

	$.get("../../../utility/functions.php", {
		functionCall: "getInterestRequestList",
		ownerID: ownerID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		$("#ajax-container").html("<p class='ir-list-notif'>TIPS: Missing request page? You might already delete it or the recepient has rejected your request</p>");

		if (response.status == "found") {
			for (let i in response.list) {
				getProgramPage(response.list[i].programPageID, response.list[i].requestID, response.list[i].businessName);
			}
		} else {
			$("#ajax-container").html("<p class='ir-list-notif'>No interest request sent</p>");
		}
 	});
}

function getProgramPage(pageID, requestID, businessName) {

	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		const content = `
		<div class='request-card'>
			<p class="request-card-title">
				From: ${businessName}<br>
				To: ${response.programName} <span class='request-card-category'>${response.categoryName}</span> <span class='request-card-viewlevel'>${response.viewLevel}</span>
			</p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
			<a class="request-card-link" href='interest_request.php?requestID=${requestID}'>Request Page</a>
		</div>`;

		$("#ajax-container").append(content);
	});
}

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logout"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}

function getOwnerInfo() {
	const ajaxContainer = $("#ajax-owner-name");
	const ownerID = parseInt(ajaxContainer.attr("data-owner-id"));
	$.get("../../../utility/functions.php", {
		functionCall: "getOwnerInfo",
		ownerID: ownerID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		ajaxContainer.html(response.ownerName);
	});
}

function getBusinessPageList() {
	const ajaxContainer = $("#ajax-container");
	const ownerID = parseInt($("#ajax-owner-name").attr("data-owner-id"));

	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPageList",
		ownerID: ownerID
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "";
		if (response != "") {
			for (let i in response) {
				content += `
				<div class="business-card">
					<p class="business-card-title">
						<span class="business-card-name">${response[i].businessName}</span> 
						<span class="business-card-tag">
							<span class="business-card-category">${response[i].categoryName}</span>
							<span class="business-card-viewlevel">${response[i].viewLevel}</span>
						</span>
					</p>
					<p class="business-card-problem">Problem:<br>${response[i].shortProblemDesc}</p>
					<a class="business-card-link" href="business_page.php?pageID=${response[i].pageID}&pageTitle=${response[i].businessName}">Full Page</a>
				</div>
				`;
			}
			ajaxContainer.html(content);
		} else {
			ajaxContainer.html("<p class='bp-list-notif'>No business page available</p>");
		}
	});
}