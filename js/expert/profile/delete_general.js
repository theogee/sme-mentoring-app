$(document).ready(() => {
	// console.log("hello world");
	getGeneralProgramPage();

	$("#delete-yes").on("click", deleteGeneralProgramPage);
	$("#delete-no").on("click", () => {
		history.back();
	})
});

function getGeneralProgramPage() {
	const pageID = parseInt($("#ajax-container").attr("data-page-id"));

	const ajaxContainer = $("#ajax-container");

	$.get("../../../utility/functions.php", {
		functionCall: "getGeneralProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		const content = `
		<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="program-viewlevel">${response.viewLevel}</span></p>
		<p>Focus Problem:<br>${response.focusProblemDesc}</p>`;

		ajaxContainer.html(content);
	});
}

function deleteGeneralProgramPage() {
	const pageID = parseInt($("#ajax-container").attr("data-page-id"));

	$.post("../../../utility/functions.php", {
		functionCall: "deleteGeneralProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "profile.php";
		else 
			alert(response.message);
	});
}