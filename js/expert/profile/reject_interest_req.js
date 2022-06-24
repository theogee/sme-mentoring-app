$(document).ready(() => {
	getInterestRequestPage();

	$("#delete-yes").on("click", deleteInterestRequest);
	$("#delete-no").on("click", () => {
		history.back();
	})
});

function deleteInterestRequest() {
	const requestID = parseInt($("#ajax-container").attr("data-request-id"));

	$.post("../../../utility/functions.php", {
		functionCall: "deleteInterestRequest",
		requestID: requestID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "profile.php";
		else 
			alert(response.message);
	});
}

function getInterestRequestPage() {
	const requestID = parseInt($("#ajax-container").attr("data-request-id"));

	$.get("../../../utility/functions.php", {
		functionCall: "getInterestRequestPage",
		requestID: requestID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		if (response.status != "success") {
			$("body").html("<p>An error occured: request page not found</p>");
			return;
		}
		
		const businessPageID = parseInt(response.businessPageID);
		const programPageID = parseInt(response.generalPageID);

		getBusinessPage(businessPageID);
		getGeneralProgramPage(programPageID);
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
			<p>Problem:<br>${response.shortProblemDesc}</p>
		</div>`;
		$("#ajax-business-page").html(content);
	});
}

function getGeneralProgramPage(programPageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: programPageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		const content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
		</div>`;
		$("#ajax-program-page").html(content);
	});
}