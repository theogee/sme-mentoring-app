$(document).ready(() => {
	// console.log("hello world");
	getBusinessPage();
	checkBusinessPageInAnyRequest();
	getMentoringOfferList();

	$("#delete-btn").on("click", () => {
		window.location = "delete_page.php";
	});

	$("#logout-btn").on("click", logout);
});

function getMentoringOfferList() {
	const pageID = parseInt($("body").attr("data-page-id"));
	$.get("../../../utility/functions.php", {
		functionCall: "getMentoringOfferList",
		businessPageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		if (response.status == "found") {
			for (let i in response.list) {
				if (response.list[i].generalPageID != null)
					getGeneralProgramPage(response.list[i].generalPageID, response.list[i].requestID);
				else
					getSpecifiedProgramPage(response.list[i].specifiedPageID, response.list[i].requestID);
			}
		} else {
			$("#ajax-mentoring-offer").append("<p>No request</p>");
		}
	});
}

function getGeneralProgramPage(pageID, requestID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		const content = `
		<div class="program-page-card">
			<p class="program-name">${response.programName} <span class="program-viewlevel">${response.viewLevel}</span></p>
			<p>${response.expertName}</p>
			<a class="offer-request-link" href='offer_request.php?requestID=${requestID}'>Detail</a>
		</div>`;

		$("#ajax-mentoring-offer").append(content);
	});
}

function getSpecifiedProgramPage(pageID, requestID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		const content = `
		<div class="program-page-card">
			<p class="program-name">${response.programName} <span class="specified-tag">specified</span></p>
			<p>${response.expertName}</p>
			<a class="offer-request-link" href='offer_request.php?requestID=${requestID}'>Detail</a>
		</div>`;

		$("#ajax-mentoring-offer").append(content);
	});
}

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logout"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}

function checkBusinessPageInAnyRequest() {
	const pageID = parseInt($("body").attr("data-page-id"));
	$.get("../../../utility/functions.php", {
		functionCall: "checkBusinessPageInAnyRequest",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "found") {
			$("#delete-btn").prop("disabled", true);
			$("#ajax-notif").css("display", "block");
			$("#ajax-notif").append("<p>NOTIFICATION: This page couldn't be deleted. To delete this page, please close any interest, offer request, or mentor room on this page.</p>");
		}
	});
}

function getBusinessPage() {
	const pageID = parseInt($("body").attr("data-page-id"));

	const businessInfoContainer = $("#ajax-business-info");
	const businessProblemContainer = $("#ajax-business-problem");
	const businessDescContainer = $("#ajax-business-desc");
	const businessProposalContainer = $("#ajax-business-proposal");

	// console.log(pageID);
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		let content = `
		<h1>${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></h1>
		<p class="business-location">${response.cityName}, ${response.provinceName}</p>
		<p class="short-problem">Problem:<br>${response.shortProblemDesc}</p>`;

		businessInfoContainer.html(content);

		content = `
		<p>${response.longProblemDesc}</p>`;

		businessProblemContainer.html(content);

		content = `
		<p>${response.about}</p>`;

		businessDescContainer.html(content);

		if (response.filePath == "") 
			content = "<p>Not Available</p>";
		else 
			content = `<a href="../../../${response.filePath}" download="${response.businessName}">business-proposal</a>`;

		businessProposalContainer.html(content);
	});
}