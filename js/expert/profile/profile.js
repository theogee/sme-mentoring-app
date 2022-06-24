$(document).ready(() => {
	const generalProgLink = $("#general-prog-link");
	const specifiedProgLink = $("#specified-prog-link");
	const offerRequestLink = $("#offer-request-link");
	const mentorRoomLink = $("#mentor-room-link");

	// console.log("hello world");
	getGeneralProgramList();
	getExpertNotification();

	generalProgLink.on("click", () => {
		if (!generalProgLink.hasClass("selected-link")) {
			getGeneralProgramList();
			generalProgLink.addClass("selected-link");
			specifiedProgLink.removeClass("selected-link");
			offerRequestLink.removeClass("selected-link");
			mentorRoomLink.removeClass("selected-link");
		}
	});

	specifiedProgLink.on("click", () => {
		if (!specifiedProgLink.hasClass("selected-link")) {
			getSpecifiedProgramList();
			// console.log("getSpecifiedProgramList");
			specifiedProgLink.addClass("selected-link");
			generalProgLink.removeClass("selected-link");
			offerRequestLink.removeClass("selected-link");
			mentorRoomLink.removeClass("selected-link");
		}
	});

	offerRequestLink.on("click", () => {
		if (!offerRequestLink.hasClass("selected-link")) {
			getOfferRequestList();
			offerRequestLink.addClass("selected-link");
			generalProgLink.removeClass("selected-link");
			specifiedProgLink.removeClass("selected-link");
			mentorRoomLink.removeClass("selected-link");
		}
	});

	mentorRoomLink.on("click", () => {
		if (!mentorRoomLink.hasClass("selected-link")) {
			getMentorRoomListForExpert();
			mentorRoomLink.addClass("selected-link");
			generalProgLink.removeClass("selected-link");
			specifiedProgLink.removeClass("selected-link");
			offerRequestLink.removeClass("selected-link");
		}
	});

	$("#ajax-notif").on("click", ".notif-card > .close-notif-btn", e => {
		const notifID = e.currentTarget.getAttribute("data-notif-id");
		deleteExpertNotification(notifID);
	});

	$("#logout-btn").on("click", logout);
});

function getMentorRoomListForExpert() {
	const expertID = $("#expert-name").attr("data-expert-id");

	$("#ajax-container").html("");
	
	$.get("../../../utility/functions.php", {
		functionCall: "getMentorRoomListForExpert",
		expertID: expertID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "found") {
			for (let i in response.list) {
				getMentorRoomCard(response.list[i].businessPageID, response.list[i].roomID, response.list[i].programName);
			}
		} else {
			$("#ajax-container").html("<p class='mr-list-notif'>No mentor room established</p>");
		}
	});
}

function getMentorRoomCard(businessPageID, roomID, programName) {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: businessPageID
	}, data => {
		const response = JSON.parse(data);
		const expertName = $("#expert-name").html();
		const content = `
		<div class='mentor-room-card'>
			<p class="mentor-room-card-title">
				Program: ${programName}<br>
				Business: ${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span>
			</p>
			<p>Problem: ${response.shortProblemDesc}</p>
			<p>Mentored By: ${expertName}</p>
			<a class="mentor-room-link" href='../mentor_room/home.php?roomID=${roomID}'>Mentor Room</a>
		</div>`;

		$("#ajax-container").append(content);
	});
}

function deleteExpertNotification(notifID) {
	const expertID = $("#expert-name").attr("data-expert-id");
	$.post("../../../utility/functions.php", {
		functionCall: "deleteExpertNotification",
		expertID: expertID,
		notifID: notifID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "success")
			getExpertNotification();
		else
			alert(response.message); 
	});
}

function getExpertNotification() {
	const expertID = $("#expert-name").attr("data-expert-id");
	$.get("../../../utility/functions.php", {
		functionCall: "getExpertNotification",
		expertID: expertID
	}, data => {
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
			$("#ajax-notif").html(content);
	});
}

function getOfferRequestList() {
	const expertID = parseInt($("#expert-name").attr("data-expert-id"));

	$("#ajax-container").html("");

	$.get("../../../utility/functions.php", {
		functionCall: "getOfferRequestList",
		expertID: expertID
	}, data => {
		const response = JSON.parse(data);
		
		$("#ajax-container").html("<p class='or-list-notif'>TIPS: Missing request page? You might already delete it or the recepient has rejected your request</p>");

		if (response.status == "found") {
			for (let i in response.list) {
				if (response.list[i].generalPageID != null)
					getBusinessPage(response.list[i].businessPageID, response.list[i].requestID, response.list[i].generalProgramName);
				else
					getBusinessPage(response.list[i].businessPageID, response.list[i].requestID, response.list[i].specifiedProgramName);
			}
		} else {
			$("#ajax-container").html("<p class='or-list-notif'>No offer request sent</p>");
		}
	});
}

function getBusinessPage(pageID, requestID, programName) {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		const content = `
		<div class='request-card'>
			<p class="request-card-title">
				From: ${programName}<br>
				To: ${response.businessName} <span class="request-card-category">${response.categoryName}</span> <span class="request-card-viewlevel">${response.viewLevel}</span>
			</p>
			<p>Problem:<br>${response.shortProblemDesc}</p>
			<p>Owned By: ${response.ownerName}</p>
			<a class="request-card-link" href='offer_request.php?requestID=${requestID}'>Request Page</a>
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

function getGeneralProgramList() {
	const ajaxContainer = $("#ajax-container");
	const expertID = parseInt($("#expert-name").attr("data-expert-id"));

	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramList",
		expertID: expertID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		let content = "";

		if (response != "" ) {
			for (let i in response) {
				content += `
				<div class="program-card">
					<p class="program-card-title">
						<span class="program-card-name">${response[i].programName}</span> 
						<span class="program-card-tag">
							<span class="program-card-category">${response[i].categoryName}</span> 
							<span class="program-card-viewlevel">${response[i].viewLevel}</span>
						</span>
					</p>
					<p class="program-card-focus">Focus problem:<br>${response[i].focusProblemDesc}</p>
					<a class="program-card-link" href="general_page.php?pageID=${response[i].pageID}&pageTitle=${response[i].programName}">Full Page</a>
				</div>
				`;
			}
			ajaxContainer.html(content);
		} else {
			$("#ajax-container").html("<p class='pp-list-notif'>No general program page available</p>");
		}

	});
}

function getSpecifiedProgramList() {
	const ajaxContainer = $("#ajax-container");
	const expertID = parseInt($("#expert-name").attr("data-expert-id"));

	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramList",
		expertID: expertID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		let content = "";

		if (response != "") {
			for (let i in response) {
				content += `
				<div class="program-card">
					<p class="program-card-title">
						<span class="program-card-name">${response[i].programName}</span> 
						<span class="program-card-tag">
							<span class="program-card-category">${response[i].categoryName}</span>
							<span class="specified-tag">specified</span>
						</span>
					</p>
					<p class="program-card-focus">Focus problem:<br>${response[i].focusProblemDesc}</p>
					<a class="program-card-link" href="specified_page.php?pageID=${response[i].pageID}&pageTitle=${response[i].programName}">Full Page</a>
				</div>
				`;
			}
			ajaxContainer.html(content);
		} else {
			ajaxContainer.html("<p class='pp-list-notif'>No specified program page available</p>");
		}
	});
}