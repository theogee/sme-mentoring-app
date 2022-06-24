const requestID = parseInt($("body").attr("data-request-id"))

$(document).ready(() => {
	getInterestRequestPage();

	$("#select-btn").on("click", () => {
		if ($("#ajax-specified-page-list").css("display") == "none")
			$("#ajax-specified-page-list").css("display", "block");
	});

	$("#cancel-btn").on("click", () => {
		$("#ajax-specified-page-list").css("display", "none");
	});

	$("#ajax-container").on("click", ".specified-page", e => {
		const specifiedPageID = e.currentTarget.getAttribute("data-specified-page-id");

		// set specified page id on the db
		setSpecifiedProgramPageOnInterestRequest(specifiedPageID);
		
		getSpecifiedProgramPage(specifiedPageID);
		$("#select-btn").css("display", "none");
		$("#ajax-specified-page-list").css("display", "none");

		// update the notification
		checkSpecifiedProgramPageAvailabilityForInterestRequest(null, "none");
	});

	$("#reject-btn").on("click", () => {
		window.location = "reject_interest_req.php";
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

function setSpecifiedProgramPageOnInterestRequest(specifiedPageID) {
	$.post("../../../utility/functions.php", {
		functionCall: "setSpecifiedProgramPageOnInterestRequest",
		requestID: requestID,
		specifiedPageID: specifiedPageID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "error")
			alert(response.message);
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
		} else {
			const expertID = parseInt(response.expertID);
			$("#select-btn").css("display", "inline");
			getAvailableSpecifiedProgramListForBusinessPage(expertID);
		}

		// get notification
		checkSpecifiedProgramPageAvailabilityForInterestRequest(response.potPath, response.transactionStatus);
	});
} 

function checkSpecifiedProgramPageAvailabilityForInterestRequest(potPath, transactionStatus) {
	$.get("../../../utility/functions.php", {
		functionCall: "checkSpecifiedProgramPageAvailabilityForInterestRequest",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "available") {
			
			if (transactionStatus == "rejected") {
				$("#ajax-notif").html("<p>NOTIFICATION: Transaction rejected. Waiting for business owner to resolve the problem</p>");
				$("#reject-btn").prop("disabled", true);
			}
			else if (potPath !== null) {
				$("#ajax-notif").html("<p>NOTIFICATION: Transaction has been done. Waiting for admin verification to establish mentor room</p>");
				$("#reject-btn").prop("disabled", true);
			} else 
				$("#ajax-notif").html("<p>NOTIFICATION: Specified program page is available. Waiting for business owner to establish Mentor Room!</p>");
		}
		else
			$("#ajax-notif").html("<p>NOTIFICATION: Specified program page not available. Expert need to provide specified program page in order for business owner to establish Mentor Room! </p>");
	});
}

function getAvailableSpecifiedProgramListForBusinessPage(expertID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getAvailableSpecifiedProgramListForBusinessPage",
		expertID: expertID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		
		let content = "";

		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<div class="specified-page" style="cursor:pointer" data-specified-page-id="${response.list[i].pageID}">
					<p class="program-name">${response.list[i].programName} <span class="program-category">${response.list[i].categoryName}</span> <span class="specified-tag">specified</span></p>
					<p>Focus Problem:<br>${response.list[i].focusProblemDesc}</p>
					<a class="program-page-link" href="specified_page.php?pageID=${response.list[i].pageID}&pageTitle=${response.list[i].programName}">Full Page</a>
				</div>`;
			}
		} else {
			content = "<p>No specified program page available. You can create new specified program page or closed previous sent request from existing specified program page</p>";
		}

		$("#ajax-container").html(content);
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
			<a class="program-page-link" href="specified_page.php?pageID=${response.pageID}&pageTitle=${response.programName}">Full Page</a>
		</div>`;

		$("#ajax-specified-page").html(content);
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
			<a class="business-page-link" href="../search/business_page.php?pageID=${businessPageID}&pageTitle=${response.businessName}">Full Page</a>
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
			<a class="program-page-link" href="general_page.php?pageID=${response.pageID}&pageTitle=${response.programName}">Full Page</a>
		</div>`;
		$("#ajax-general-page").html(content);
	});
}