const businessPageID = $("body").attr("data-page-id");

$(document).ready(() => {
	const generalProgLink = $("#general-prog-link");
	const specifiedProgLink = $("#specified-prog-link");

	getBusinessPage();

	getAvailableGeneralProgramPageListForBusinessPage();

	$("#send-offer-request-btn").on("click", () => {
		if ($("#program-list-dialog").css("display") == "none") {
			$("#program-list-dialog").css("display", "block");
		}
	});

	$("#cancel-btn").on("click", () => {
		$("#program-list-dialog").css("display", "none");
	});

	generalProgLink.on("click", () => {
		if (!generalProgLink.hasClass("selected-link")) {
			getAvailableGeneralProgramPageListForBusinessPage();
			generalProgLink.addClass("selected-link");
			specifiedProgLink.removeClass("selected-link");
		}
	});

	specifiedProgLink.on("click", () => {
		if (!specifiedProgLink.hasClass("selected-link")) {
			getAvailableSpecifiedProgramListForBusinessPage();
			// console.log("getSpecifiedProgramList");
			specifiedProgLink.addClass("selected-link");
			generalProgLink.removeClass("selected-link");
		}
	});

	$("#ajax-container").on("click", "div[class$='program-page']", e => {
		if (e.currentTarget.hasAttribute("data-specified-page-id"))  {
			const programPageID = e.currentTarget.getAttribute("data-specified-page-id");
			window.location = `create_request.php?programPageID=${programPageID}&programPageType=specified`;
		} else {
			const programPageID = e.currentTarget.getAttribute("data-general-page-id");
			window.location = `create_request.php?programPageID=${programPageID}&programPageType=general`;			
		}
	});

	$("#logout-btn").on("click", logout);
});

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logoutOwner"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}

function getAvailableSpecifiedProgramListForBusinessPage() {
	const ajaxContainer = $("#ajax-container");
	const expertID = $("#program-list-dialog").attr("data-expert-id");

	$.get("../../../utility/functions.php", {
		functionCall: "getAvailableSpecifiedProgramListForBusinessPage",
		expertID: expertID
	}, data => {
		const response = JSON.parse(data);

		let content = "";

		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<div class="specified-program-page" style="cursor:pointer" data-specified-page-id="${response.list[i].pageID}">
					<p class="program-name">${response.list[i].programName} <span class="program-category">${response.list[i].categoryName}</span> <span class="specified-tag">specified</span></p>
					<p>Focus Problem:<br>${response.list[i].focusProblemDesc}</p>
					<a class="program-page-link" href="specified_page.php?pageID=${response.list[i].pageID}&pageTitle=${response.list[i].programName}">Full Page</a>
				</div>`;
			}
		} else {
			content = "<p>No specified program page available. You can create new specified program page or close previous sent request from existing specified program page</p>"
		}

		ajaxContainer.html(content);

	});
}



function getAvailableGeneralProgramPageListForBusinessPage() {
	const ajaxContainer = $("#ajax-container");
	const expertID = $("#program-list-dialog").attr("data-expert-id");

	$.get("../../../utility/functions.php", {
		functionCall: "getAvailableGeneralProgramPageListForBusinessPage",
		expertID: expertID,
		businessPageID: businessPageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		let content = "";

		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<div class='general-program-page' style='cursor:pointer' data-general-page-id='${response.list[i].generalPageID}'>
					<p class="program-name">${response.list[i].generalProgramName} <span class="program-category">${response.list[i].categoryName}</span> <span class="program-viewlevel">${response.list[i].viewLevel}</span></p>
					<p>Focus problem:<br>${response.list[i].focusProblemDesc}</p>
					<a class="program-page-link" href="../profile/general_page.php?pageID=${response.list[i].generalPageID}&pageTitle=${response.list[i].generalProgramName}">Full Page</a>
				</div>
				<br>`;
			}
		} else {
			content = "<p>No general program page available. You can create new general program page or close previous sent request from existing general program page</p>"
		}

		ajaxContainer.html(content);
	});
}


function getBusinessPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: businessPageID
	}, data => {
		const response = JSON.parse(data);

		let content = `
		<h1 class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></h1>
		<p>${response.cityName}, ${response.provinceName}</p>
		<p>Problem:<br>${response.shortProblemDesc}</p>
		<p>Owned By: ${response.ownerName}</p>`;

		$("#ajax-business-info").html(content);

		content = `<p>${response.longProblemDesc}</p>`;

		$("#ajax-business-problem").html(content);

		content = `<p>${response.about}</p>`;

		$("#ajax-business-desc").html(content);

		if (response.filePath == "") 
			content = "<p>Not Available</p>"
		else
			content = `<a href="../../../${response.filePath}" download="${response.businessName}">business-proposal</a>`;

		$("#ajax-business-proposal").html(content);
	});
}