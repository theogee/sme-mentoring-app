$(document).ready(() => {
	getOfferRequestPage();
	$("#delete-yes").on("click", deleteOfferRequest);
	$("#delete-no").on("click", () => {
		history.back();
	})
});

function deleteOfferRequest() {
	const requestID = $("#ajax-container").attr("data-request-id");

	$.post("../../../utility/functions.php", {
		functionCall: "deleteOfferRequest",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "profile.php";
		else
			alert(response.message);
	});
}

function getOfferRequestPage() {
	const requestID = $("#ajax-container").attr("data-request-id");

	$.get("../../../utility/functions.php", {
		functionCall: "getOfferRequestPage",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);

		if (response.status != "success") {
			$("body").html("<p>An error occured: request page not found</p>");
			return;
		}

		if (response.generalPageID != null)
			getGeneralProgramPage(response.generalPageID);
		else
			getSpecifiedProgramPage(response.specifiedPageID);

		getBusinessPage(response.businessPageID);
	});
}

function getGeneralProgramPage(pageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		let content = `<h1>Reject Offer Request by ${response.expertName}</h1>`;
		$("#ajax-request-header").html(content);

		content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
		</div>`;

		$("#ajax-program-page").html(content);
	});
}

function getSpecifiedProgramPage(pageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		let content = `<h1>Reject Offer Request by ${response.expertName}</h1>`;
		$("#ajax-request-header").html(content);

		content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
		</div>`;

		$("#ajax-program-page").html(content);
	});
}

function getBusinessPage(pageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		const content = `
		<div>
			<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
			<p>Problem:<br>${response.shortProblemDesc}</p>
			<p>Owner By: ${response.ownerName}</p>
		</div>`;

		$("#ajax-business-page").html(content);
	});
}