const roomID = $("body").attr("data-room-id");
const posterID = $("body").attr("data-poster-id");

$(document).ready(() => {
	setupFileUploadBtn();

	$("#post-btn").on("click", () => {
		addResource();
	});

	$("#exit-btn").on("click", () => {
		window.location = "../profile/profile.php";
	});
});

function addResource() {
	const title = $("#title").val();
	const msg = $("#msg").html();

	const file = $("#file")[0].files[0];

	if (isEmpty(title)) {
		alert("Title must not be empty");
		return;
	}

	if (!isEmpty(file)) {
		let formdata = new FormData();

		formdata.append("functionCall", "addResource");
		formdata.append("roomID", roomID);
		formdata.append("posterID", posterID);
		formdata.append("userType", "expert");
		formdata.append("title", title);
		formdata.append("message", msg);
		formdata.append("file", file);

		$.ajax({
			url: "../../../utility/functions.php",
			type: "POST",
			data: formdata,
			contentType: false,
			processData: false,
			success: data => {
				const response = JSON.parse(data);
				if (response.status == "success")
					window.location = "resources.php";
				else
					alert(response.message);
			}
		});

	} else {
		$.post("../../../utility/functions.php", {
			functionCall: "addResource",
			roomID: roomID,
			posterID: posterID,
			userType: "expert",
			title: title,
			message: msg
		}, data => {
			const response = JSON.parse(data);
			if (response.status == "success")
				window.location = "resources.php";
			else
				alert(response.message);
		});
	}
}

function isEmpty(value) {
	return (value == '' || value == null) ? true : false;
}

function setupFileUploadBtn() {
	const chooseFileBtn = $("#choose-file-btn");
	const removeFileBtn = $("#remove-file-btn");

	const fileInput = $("#file");
	// console.log("hello world");
	chooseFileBtn.on("click", e => {
		e.preventDefault();
		fileInput.click();
	});

	removeFileBtn.on("click", e => {
		e.preventDefault();
		$("#file-name").html("No file chosen");
		chooseFileBtn.css("display", "inline");
		removeFileBtn.css("display", "none");
		fileInput.val("");
	});

	fileInput.on("change", () => {
		const file = fileInput[0].files[0];

		if (!isEmpty(fileInput.val())) {
			const filesize = file.size/1000;
			$("#file-name").html(file.name + " " + filesize.toFixed(1) + "KB");
			removeFileBtn.css("display", "inline");
			chooseFileBtn.css("display", "none");
		}
	});
}