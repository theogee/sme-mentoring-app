$(document).ready(() => {
	// console.log("hello world");
	getSpecifiedProgramPage();

	$("#delete-yes").on("click", deleteSpecifiedProgramPage);
	$("#delete-no").on("click", () => {
		history.back();
	})
});

function getSpecifiedProgramPage() {
	const pageID = parseInt($("#ajax-container").attr("data-page-id"));

	const ajaxContainer = $("#ajax-container");

	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		const content = `
		<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></p>
		<p>Focus Problem:<br>${response.focusProblemDesc}</p>`;

		ajaxContainer.html(content);
	});
}

function deleteSpecifiedProgramPage() {
	const pageID = parseInt($("#ajax-container").attr("data-page-id"));

	$.post("../../../utility/functions.php", {
		functionCall: "deleteSpecifiedProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "profile.php";
		else 
			alert(response.message);
	});
}