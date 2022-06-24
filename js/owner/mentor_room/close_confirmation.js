const roomID = $("body").attr("data-room-id");

$(document).ready(() => {
	getMentorRoom();

	$("#exit-btn").on("click", () => {
		window.location = "../profile/profile.php";
	});

	$("#close-no").on("click", () => {
		window.history.back();
	});

	$("#close-yes").on("click", () => {
		closeMentorRoom();
	});
});

function closeMentorRoom() {
	$.post("../../../utility/functions.php", {
		functionCall: "closeMentorRoom",
		roomID: roomID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "../profile/profile.php";
		else
			alert(response.message);
	});
}

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
			<p>Owned By: ${response.ownerName}</p>
			<p>Email: ${response.ownerEmail}</p>
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
			<p>Mentored By: ${response.expertName}</p>
			<p>Email: ${response.expertEmail}</p>
		</div>`;
		$("#ajax-program-page").html(content);
	});
}