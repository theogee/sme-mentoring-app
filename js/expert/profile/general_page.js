$(document).ready(() => {
	const mentoringProposal = $("#mentoring-proposal");
	const filePath = mentoringProposal.attr("data-file-path");
	const programName = mentoringProposal.attr("data-program-name");

	getInterestedBusinessList();
	checkGeneralProgramPageInAnyRequest();

	$("#delete-btn").on("click", () => {
		window.location = "delete_general.php";
	});

	$("#logout-btn").on("click", logout);

	if (filePath == "") 
			content = "<p>Not Available</p>";
		else 
			content = `<a href="../../../${filePath}" download="${programName}">mentoring-proposal</a>`;

	mentoringProposal.html(content);
});

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logout"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}

function checkGeneralProgramPageInAnyRequest() {
	const pageID = parseInt($("#ajax-interested-business").attr("data-page-id"));
	$.get("../../../utility/functions.php", {
		functionCall: "checkGeneralProgramPageInAnyRequest",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "found") {
			$("#delete-btn").prop("disabled", true);
			$("#ajax-notif").css("display", "block");
			$("#ajax-notif").append("<p>NOTIFICATION: This page couldn't be deleted. To delete this page, please reject or close any interest and offer request on this page.</p>");
		}
	});
}

function getInterestedBusinessList() {
	const pageID = parseInt($("#ajax-interested-business").attr("data-page-id"));
	$.get("../../../utility/functions.php", {
		functionCall: "getInterestedBusinessList",
		programPageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		if (response.status == "found") {
			for (let i in response.list) {
				getBusinessPage(response.list[i].businessPageID, response.list[i].requestID);
			}
		} else {
			$("#ajax-interested-business").html("<p>No request</p>");
		}
	});
}

function getBusinessPage(pageID, requestID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		const content = `
		<div class="business-card">
			<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
			<p>${response.ownerName}</p>
			<a class="business-page-link" href='interest_request.php?requestID=${requestID}'>Detail</a>
		</div>`;

		$("#ajax-interested-business").append(content);
	});
}