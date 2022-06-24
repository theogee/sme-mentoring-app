const requestID = $("body").attr("data-request-id");

$(document).ready(() => {
	getOfferRequestPage();
	$("#reject-btn").on("click", () => {
		window.location = "reject_offer_req.php";
	});

	$("#establish-btn").on("click", () => {
		window.location = "transaction.php?req=or";
	});

	$("#logout-btn").on("click", logout);
});

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logout"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}

function getOfferRequestPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getOfferRequestPage",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);

		if (response.status != "success") {
			window.location = "profile.php";
		}

		if (response.generalPageID != null) {
			getGeneralProgramPage(response.generalPageID);
			$("#specified-page-interface").css("display", "block");

			if (response.specifiedPageID != null) {
				getSpecifiedProgramPage(response.specifiedPageID, $("#ajax-specified-page"))
				$("#establish-btn").prop("disabled", false);
				
				if (response.transactionStatus == "rejected") {
					$("#ajax-notif").html("<p>NOTIFICATION: Transaction has been rejected. Please check your email or contact support@iqoniq.com for further information</p>");
					$("#reject-btn").prop("disabled", true);
				}
				else if (response.potPath !== null) {
					$("#ajax-notif").html("<p>NOTIFICATION: Proof of transaction has been uploaded. Waiting for admin verification to establish mentor room</p>");
					$("#reject-btn").prop("disabled", true);
				}
				else
					$("#ajax-notif").html(`<p>NOTIFICATION: Specified program page is available. Mentor Room can be established!</p>`);
				}
			else {
				$("#ajax-specified-page").html("<p>Not Available</p>");
				$("#ajax-notif").html("<p>NOTIFICATION: Specified program page is not available. Ask the expert to provide the specified program page in order to establish Mentor Room!</p>");
			}
		}
		else {
			$("#specified-page-interface").remove();
			getSpecifiedProgramPage(response.specifiedPageID, $("#ajax-program-page"), true);
			$("#establish-btn").prop("disabled", false);
			$("#ajax-notif").html(`<p>NOTIFICATION: Specified program page is available. Mentor Room can be established!</p>`);
		}

		if (response.potPath !== null) {
			$("#ajax-notif").html("<p>NOTIFICATION: Proof of transaction has been uploaded. Waiting for admin verification to establish mentor room</p>");
			$("#reject-btn").prop("disabled", true);
		} else if (response.transactionStatus == "rejected") {
			$("#ajax-notif").html("<p>NOTIFICATION: Transaction has been rejected. Please check your email or contact support@iqoniq.com for further information</p>");
			$("#reject-btn").prop("disabled", true);
		}

		getBusinessPage(response.businessPageID);
	});
}

function getGeneralProgramPage(generalPageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: generalPageID
	}, data => {
		const response = JSON.parse(data);

		let content = `<h1>Offer Request by ${response.expertName}</h1>`;
		$("#ajax-request-header").html(content);

		content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<a class="program-page-link" href="../search/program_page.php?pageID=${response.pageID}&pageTitle=${response.programName}&type=general">Full Page</a>
		</div>`;

		$("#ajax-program-page").html(content);
	});
}

function getSpecifiedProgramPage(specifiedPageID, ajaxContainer, initProgram = false) {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: specifiedPageID
	}, data => {
		const response = JSON.parse(data);

		let content = "";
		if (initProgram) {
			content = `<h1>Offer Request by ${response.expertName}</h1>`;
			$("#ajax-request-header").html(content);
		}

		content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
			<a class="program-page-link" href="../search/program_page.php?pageID=${response.pageID}&pageTitle=${response.programName}&type=specified">Full Page</a>
		</div>`;

		ajaxContainer.html(content);
	});
}

function getBusinessPage(businessPageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: businessPageID
	}, data => {
		const response = JSON.parse(data);

		const content = `
		<div>
			<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
			<p>Short Problem Description:<br>${response.shortProblemDesc}</p>
			<p>Owned By: ${response.ownerName}</p>
			<a class="business-page-link" href="../profile/business_page.php?pageID=${businessPageID}&pageTitle=${response.businessName}">Full Page</a>
		</div>`;
		
		$("#ajax-business-page").html(content);
	});
}