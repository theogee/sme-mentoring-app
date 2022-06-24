const roomID = $("body").attr("data-room-id");

$(document).ready(() => {
	getMentorRoom();

	$("#exit-btn").on("click", () => {
		window.location = "../profile/profile.php";
	});
});

function getMentorRoom() {
	$.get("../../../utility/functions.php", {
		functionCall: "getMentorRoom",
		roomID: roomID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "found") {
			getBusinessPage(response.businessPageID);
			getSpecifiedProgramPage(response.specifiedPageID);
			$("#ajax-created-date").html(`<p>Created at: ${response.createdDate}</p>`);
		} else {
			alert("An error occured: page not defined");
			window.location = "../profile/profile.php";
		}
	});
}

function getBusinessPage(pageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		const content = `
		<div>
			<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
			<p>Problem:<br>${response.shortProblemDesc}</p>
			<p>Owned By: ${response.ownerName}</p>
			<a class="business-page-link" href="../search/business_page.php?pageID=${response.pageID}&pageTitle=${response.businessName}">Full Page</a>
		</div>`;
		$("#ajax-business-page").html(content);
	});
}

function getSpecifiedProgramPage(pageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		$("#ajax-duration").html(`<p>Duration: ${response.programDuration} months</p>`);

		const content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Mentored By: ${response.expertName}</p>
			<a class="program-page-link" href="../profile/specified_page.php?pageID=${response.pageID}&pageTitle=${response.programName}">Full Page</a>
		</div>`;
		$("#ajax-program-page").html(content);
	});
}