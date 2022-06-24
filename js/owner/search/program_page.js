const programPageID = parseInt($("body").attr("data-page-id")); 
const type = $("body").attr("data-type");

$(document).ready(() => {
	if (type == "general") {
		getGeneralProgramPage();

		getAvailableBusinessPageListForProgramPage();

		$("#send-interest-request-btn").on("click", () => {
			if ($("#business-list-dialog").css("display") == "none") {
				$("#business-list-dialog").css("display", "block");
			}
		});

		$("#cancel-btn").on("click", () => {
			$("#business-list-dialog").css("display", "none");
		});

		$("#ajax-container").on("click", ".business-page", e => {
			const businessPageID = e.currentTarget.getAttribute("data-business-page-id");
			window.location=`create_request.php?businessPageID=${businessPageID}`;
		});
	}
	else {
		$("#send-interest-request").remove();
		getSpecifiedProgramPage();
	}

	$("#logout-btn").on("click", logout);
});

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logoutOwner"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}


function getAvailableBusinessPageListForProgramPage() {
	const ajaxContainer = $("#ajax-container");
	const ownerID = parseInt($("#business-list-dialog").attr("data-owner-id"));

	$.get("../../../utility/functions.php", {
		functionCall: "getAvailableBusinessPageListForProgramPage",
		ownerID: ownerID,
		programPageID: programPageID
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "";

		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<div class='business-page' style='cursor:pointer' data-business-page-id='${response.list[i].pageID}'>
					<p class="business-name">${response.list[i].businessName} <span class="business-category">${response.list[i].categoryName}</span> <span class="business-viewlevel">${response.list[i].viewLevel}</span></p>
					<p>Problem:<br>${response.list[i].shortProblemDesc}</p>
					<a class="business-page-link" href="../profile/business_page.php?pageID=${response.list[i].pageID}&pageTitle=${response.list[i].businessName}">Full Page</a>
				</div>
				<br>
				`;
			}
		} else {
			content = "<p>No business page available. You can create new business page or closed previous sent request from existing business page</p>"; 
		}

		ajaxContainer.html(content);

	});
}

function getSpecifiedProgramPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: programPageID
	}, data => {
		const response = JSON.parse(data);

		let content = `
		<h2 class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></h2>
		<p>Focus Problem:<br>${response.focusProblemDesc}</p>
		<p>Created By: ${response.expertName}</p>`;

		$("#ajax-program-info").html(content);

		const cost = parseInt(response.cost).toLocaleString();

		content = `<p>Rp.${cost}</p>`;
		$("#ajax-cost").html(content);

		content = `<p>${response.programDuration} Month</p>`;
		$("#ajax-duration").html(content);

		content = `<p>${response.about}</p>`;
		$("#ajax-about").html(content);

		content = `<p>${response.expectedOutcome}</p>`;
		$("#ajax-expected-outcome").html(content);

		if (response.filePath == "") 
			content = "<p>Not Available</p>";
		else 
			content = `<a href="../../../${response.filePath}" download="${response.programName}">mentoring-proposal</a>`;

		$("#ajax-proposal").html(content);
	});
}

function getGeneralProgramPage() {

	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: programPageID
	}, data => {
		const response = JSON.parse(data);

		let content = `
		<h2>${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></h2>
		<p>Focus Problem:<br>${response.focusProblemDesc}</p>
		<p>Created By: ${response.expertName}</p>`;

		$("#ajax-program-info").html(content);


		const minCost = parseInt(response.minCost).toLocaleString();
		const maxCost = parseInt(response.maxCost).toLocaleString();

		content = `<p>Rp.${minCost} - Rp.${maxCost}</p>`;
		$("#ajax-cost").html(content);

		content = `<p>${response.programDuration} Month</p>`;
		$("#ajax-duration").html(content);

		content = `<p>${response.about}</p>`;
		$("#ajax-about").html(content);


		content = `<p>${response.expectedOutcome}</p>`;
		$("#ajax-expected-outcome").html(content);

		if (response.filePath == "") 
			content = "<p>Not Available</p>";
		else 
			content = `<a href="../../../${response.filePath}" download="${response.programName}">mentoring-proposal</a>`;

		$("#ajax-proposal").html(content);
	});
}