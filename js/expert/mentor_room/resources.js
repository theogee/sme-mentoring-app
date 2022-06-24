const roomID = $("body").attr("data-room-id");

$(document).ready(() => {
	getAvailableSpace();
	getResources();
	$("#add-btn").on("click", () => {
		window.location = "add_resources.php";
	});

	$("#ajax-resources").on("click", "#delete-btn", e => {
		const resourceID = e.currentTarget.getAttribute("data-resource-id");
		const filePath = e.currentTarget.getAttribute("data-file-path");
		deleteResource(resourceID, filePath);
	});

	$("#exit-btn").on("click", () => {
		window.location = "../profile/profile.php";
	});
});

function getAvailableSpace() {
	$.get("../../../utility/functions.php", {
		functionCall: "getMentorRoom",
		roomID: roomID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "found") {
			const availableSpace = parseFloat(response.availableSpace)/1000000000;
			$("#ajax-available-space").html(`<p>Available space: ${availableSpace.toFixed(3)}GB out of 1GB</p>`);
		} else {
			alert("An error occured: page not defined");
			window.location = "../profile/profile.php";
		}
	});
}

function deleteResource(resourceID, filePath) {
	$.post("../../../utility/functions.php", {
		functionCall: "deleteResource",
		resourceID: resourceID,
		filePath: filePath,
		roomID: roomID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		if (response.status == "success"){
			getResources();
			getAvailableSpace();
		}
		else
			alert(response.message);
	});
}

function getResources() {
	$.get("../../../utility/functions.php", {
		functionCall: "getResources",
		roomID: roomID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		let content = "";
		if (response.status == "found") {
			for (let i in response.list) {
				content +=`
				<div class='resource-card'>
					<p class="poster-name">${response.list[i].posterName} added a new resource</p>
					<p>Title: ${response.list[i].title}</p>`;
				if (response.list[i].message != "")
					content += `<p>Message: ${response.list[i].message}</p>`;
				if (response.list[i].filePath != "")
					content += `Attachment: <a href='../../../${response.list[i].filePath}' download='${response.list[i].fileName}'>${response.list[i].fileName}</a><br><br>`;
				content += `<button id='delete-btn' data-resource-id='${response.list[i].resourceID}' data-file-path='${response.list[i].filePath}'>delete</button></div><br>`;
			}
			$("#ajax-resources").html(content);
		} else {
			$("#ajax-resources").html("<p>No resources available. Start adding resources!</p>");
		}
	});
}