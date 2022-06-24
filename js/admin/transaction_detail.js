const requestID = $("body").attr("data-request-id");
const req = $("body").attr("data-request-type");

$(document).ready(() => {
	if (req == "ir")
		getInterestRequestPage();
	else
		getOfferRequestPage();

	$("#reject-btn").on("click", () => {
		if (req == "ir")
			rejectTransactionFromInterestRequest();
		else
			rejectTransactionFromOfferRequest();
	});

	$("#verify-btn").on("click", () => {
		createMentorRoom();
	});

	$("#logout-btn").on("click", () => {
		$.post("../../utility/functions.php", {
			functionCall: "logout"
		}, () => {
			window.location = "auth.php";
		});
	});
});

function createMentorRoom() {
	$.post("../../utility/functions.php", {
		functionCall: "createMentorRoom",
		requestType: req,
		requestID: requestID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "index.php";
		else
			alert(response.message);
	});
}

function rejectTransactionFromInterestRequest() {
	$.post("../../utility/functions.php", {
		functionCall: "rejectTransactionFromInterestRequest",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "index.php";
		else
			alert(response.message);
	});
}

function rejectTransactionFromOfferRequest() {
	$.post("../../utility/functions.php", {
		functionCall: "rejectTransactionFromOfferRequest",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "index.php";
		else
			alert(response.message);
	});
}

function getOfferRequestPage() {
	$.get("../../utility/functions.php", {
		functionCall: "getOfferRequestPage",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);

		if (response.status != "success") {
			$("body").html("<p>An error occured: request page not found</p>");
			return;
		}

		const content = `
		<p>Transaction date: ${response.transactionDate}</p>
		<a href='../../${response.potPath}' download='pot-${requestID}-${req}'>Proof of transaction</a>`;
		$("#ajax-pot-info").html(content);

		getBusinessPage(response.businessPageID);
		getSpecifiedProgramPage(response.specifiedPageID);
	});
}

function getInterestRequestPage() {

	$.get("../../utility/functions.php", {
		functionCall: "getInterestRequestPage",
		requestID: requestID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		if (response.status != "success") {
			$("body").html("<p>An error occured: request page not found</p>");
			return;
		}

		const content = `
		<p>Transaction date: ${response.transactionDate}</p>
		<a href='../../${response.potPath}' download='pot-${requestID}-${req}'>Proof of transaction</a>`;
		$("#ajax-pot-info").html(content);


		getBusinessPage(response.businessPageID);
		getSpecifiedProgramPage(response.specifiedPageID);
	}); 
}

function getBusinessPage(pageID) {
	$.get("../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		const content = `
		<div>
			<p>ID: ${response.pageID}<p>
			<p>${response.businessName} ${response.categoryName} ${response.viewLevel}</p>
			<p>Sort Problem Description:<br>${response.shortProblemDesc}</p>
			<p>Owned By: ${response.ownerName}</p>
		</div>`;

		$("#ajax-business-page").html(content);
	});
}

function getSpecifiedProgramPage(pageID) {
	$.get("../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		let content = `
		<p>Total: Rp.${parseInt(response.cost).toLocaleString()}</p>
		<p>Duration: ${response.programDuration} months</p>`;

		$("#ajax-receipt").html(content);

		content = `
		<div>
			<p>ID: ${response.pageID}<p>
			<p>${response.programName} ${response.categoryName} specified</p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
		</div>`;

		$("#ajax-specified-page").html(content);
	});
}