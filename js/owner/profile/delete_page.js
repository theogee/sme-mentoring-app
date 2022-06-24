$(document).ready(() => {
	// console.log("hello world");
	getBusinessPage();

	$("#delete-yes").on("click", deleteBusinessPage);
	$("#delete-no").on("click", () => {
		history.back();
	})
});

function getBusinessPage() {
	const pageID = parseInt($("#ajax-container").attr("data-page-id"));

	const ajaxContainer = $("#ajax-container");

	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		const content = `
		<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
		<p>Problem:<br>${response.shortProblemDesc}</p>`;

		ajaxContainer.html(content);
	});
}

function deleteBusinessPage() {
	const pageID = parseInt($("#ajax-container").attr("data-page-id"));

	$.post("../../../utility/functions.php", {
		functionCall: "deleteBusinessPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		if (response.status == "success")
			window.location = "profile.php";
		else 
			alert(response.message);
	});
}