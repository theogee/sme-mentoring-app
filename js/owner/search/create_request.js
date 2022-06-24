const businessPageID = $("body").attr("data-business-page-id");
const programPageID = $("body").attr("data-program-page-id");

$(document).ready(() => {
	getBusinessPage();
	getGeneralProgramPage();

	$("#send-btn").on("click", createInterestRequest);
});

function createInterestRequest() {
	$.post("../../../utility/functions.php", {
		functionCall: "createInterestRequest",
		businessPageID: businessPageID,
		programPageID: programPageID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = `../profile/interest_request.php?requestID=${response.requestID}`;
		else 
			alert(response.message);
	});
}

function getBusinessPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: businessPageID
	}, data => {
		const response = JSON.parse(data);

		let content = `<h1>Interest Request by ${response.ownerName}</h1>`;
		$("#ajax-request-header").html(content);

		content = `
		<div>
			<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
			<p>Short Problem Description:<br>${response.shortProblemDesc}</p>
			<a class="business-page-link" href="../profile/business_page.php?pageID=${businessPageID}&pageTitle=${response.businessName}">Full Page</a>
		</div>`;
		$("#ajax-business-page").html(content);
	});
}

function getGeneralProgramPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: programPageID
	}, data => {
		const response = JSON.parse(data);
		const content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
			<a class="program-page-link" href="program_page.php?pageID=${response.pageID}&pageTitle=${response.programName}">Full Page</a>
		</div>`;
		$("#ajax-program-page").html(content);
	});
}