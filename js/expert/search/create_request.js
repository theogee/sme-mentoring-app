const programPageID = $("body").attr("data-program-page-id");
const programPageType = $("body").attr("data-program-page-type");
const businessPageID = $("body").attr("data-business-page-id");

$(document).ready(() => {
	if (programPageType == "specified")
		getSpecifiedProgramPage();
	else 
		getGeneralProgramPage();

	getBusinessPage();

	$("#send-btn").on("click", createOfferRequest);
});

function createOfferRequest() {
	$.post("../../../utility/functions.php", {
		functionCall: "createOfferRequest",
		programPageType: programPageType,
		programPageID: programPageID,
		businessPageID: businessPageID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = `../profile/offer_request.php?requestID=${response.requestID}`;
		else
			alert(response.message);
	});
}

function getSpecifiedProgramPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: programPageID
	}, data => {
		const response = JSON.parse(data);

		let content = `<h1>Offer Request by ${response.expertName}</h1>`;
		$("#ajax-request-header").html(content);

		content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<a class="program-page-link" href="../profile/specified_page.php?pageID=${response.pageID}&pageTitle=${response.programName}">Full Page</a>
		</div>`;
		$("#ajax-program-page").html(content);
	});
}

function getGeneralProgramPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: programPageID
	}, data => {
		const response = JSON.parse(data);

		let content = `<h1>Offer Request by ${response.expertName}</h1>`;
		$("#ajax-request-header").html(content);
		
	 	content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<a class="program-page-link" href="../profile/general_page.php?pageID=${response.pageID}&pageTitle=${response.programName}">Full Page</a>
		</div>`;
		$("#ajax-program-page").html(content);
	});
}

function getBusinessPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: businessPageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		const content = `
		<div>
			<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
			<p>Short Problem Description:<br>${response.shortProblemDesc}</p>
			<p>Owned By: ${response.ownerName}</p>
			<a class="business-page-link" href="business_page.php?pageID=${response.pageID}&pageTitle=${response.businessName}">Full Page</a>
		</div>`;
		$("#ajax-business-page").html(content);
	});
}