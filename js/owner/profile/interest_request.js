const requestID = parseInt($("body").attr("data-request-id"));

$(document).ready(() => {
	getInterestRequestPage();
	$("#close-btn").on("click", () => {
		window.location = "close_interest_req.php";
	});

	$("#logout-btn").on("click", logout);

	$("#establish-btn").on("click", () => {
		window.location = "transaction.php?req=ir";
	});
});

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logout"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}

function getInterestRequestPage() {

	$.get("../../../utility/functions.php", {
		functionCall: "getInterestRequestPage",
		requestID: requestID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		if (response.status != "success") {
			window.location = "profile.php";
		}
		
		const businessPageID = parseInt(response.businessPageID);
		const generalPageID = parseInt(response.generalPageID);

		getBusinessPage(businessPageID);
		getGeneralProgramPage(generalPageID);

		if (response.specifiedPageID !== null) {
			const specifiedPageID = parseInt(response.specifiedPageID);
			getSpecifiedProgramPage(specifiedPageID);
			$("#establish-btn").prop("disabled", false);

			if (response.transactionStatus == "rejected") {
				$("#ajax-notif").html("<p>NOTIFICATION: Transaction has been rejected. Please check your email or contact support@iqoniq.com for further information</p>");
				$("#close-btn").prop("disabled", true);
			}
			else if (response.potPath !== null) {
				$("#ajax-notif").html("<p>NOTIFICATION: Proof of transaction has been uploaded. Waiting for admin verification to establish mentor room</p>");
				$("#close-btn").prop("disabled", true);
			}
			else
				$("#ajax-notif").html(`<p>NOTIFICATION: Specified program page is available. Mentor Room can be established!</p>`);
		} else {
			$("#ajax-specified-page").html("<p>Not Available</p>");
			$("#ajax-notif").append("<p>NOTIFICATION: Specified program page is not available. Ask the expert to provide the specified program page in order to establish Mentor Room!</p>");
		}
	});
} 

function getBusinessPage(businessPageID) {
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
			<p>Sort Problem Description:<br>${response.shortProblemDesc}</p>
			<a class="business-page-link" href="business_page.php?pageID=${businessPageID}&pageTitle=${response.businessName}">Full Page</a>
		</div>`;
		$("#ajax-business-page").html(content);
	});
}

function getGeneralProgramPage(generalPageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: generalPageID
	}, data => {
		const response = JSON.parse(data);

		const content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
			<a class="program-page-link" href="../search/program_page.php?pageID=${response.pageID}&pageTitle=${response.programName}&type=general">Full Page</a>
		</div>`;
		$("#ajax-general-page").html(content);
	});
}

function getSpecifiedProgramPage(specifiedPageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: specifiedPageID
	}, data => {
		const response = JSON.parse(data);

		const content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
			<a class="program-page-link" href="../search/program_page.php?pageID=${response.pageID}&pageTitle=${response.programName}&type=specified">Full Page</a>
		</div>`;
		$("#ajax-specified-page").html(content);
		
	});
}