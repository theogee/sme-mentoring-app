$(document).ready(() => {
	const mentoringProposal = $("#mentoring-proposal");
	const filePath = mentoringProposal.attr("data-file-path");
	const programName = mentoringProposal.attr("data-program-name");

	checkSpecifiedProgramPageInAnyRequest();

	$("#delete-btn").on("click", () => {
		window.location = "delete_specified.php";
	});

	$("#logout-btn").on("click", logout);

	if (filePath == "") 
			content = "<p>Not Available</p>";
		else 
			content = `<a href="../../../${filePath}" download="${programName}">mentoring-proposal</a>`;

	mentoringProposal.html(content);
});

function checkSpecifiedProgramPageInAnyRequest() {
	const pageID = $("body").attr("data-page-id");
	$.get("../../../utility/functions.php", {
		functionCall: "checkSpecifiedProgramPageInAnyRequest",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "found") {
			$("#delete-btn").prop("disabled", true);
			$("#ajax-notif").css("display", "block");
			$("#ajax-notif").append("<p>NOTIFICATION: This page couldn't be deleted. To delete this page, please reject or close any interest, offer request, or mentor room on this page.</p>");
		}
	});
}

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logout"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}