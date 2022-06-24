// to show if user remove initial file
let removeInitFile = false;

$(document).ready(() => {
	const initCategory = $("#ajax-category").attr("data-init-category-id");
	// console.log("hello world");
	getCategoryList(initCategory);

	setupFileUploadBtnWithInit();

	$("#update-btn").on("click", e => {
		e.preventDefault();
		updateSpecifiedProgramPage();
	});

	$("#logout-btn").on("click", logout);
});

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logout"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}

function isEmpty(value) {
	return (value == '' || value == null) ? true : false;
}

function setupFileUploadBtnWithInit() {
	const initFilePath = $("#init-file-path").val();
	const programName = $("#program-name").val();
	const displayText = $("#display-text");

	const chooseFileBtn = $("#choose-file-btn");
	const removeFileBtn = $("#remove-file-btn");

	const removeInitFileBtn = $("#remove-init-file-btn");

	const fileInput = $("#file");

	if (!isEmpty(initFilePath)) {
		removeInitFileBtn.css("display", "inline");
		chooseFileBtn.css("display", "none");
		displayText.html(`<a href='../../../${initFilePath}' download='${programName}'>mentoring-proposal</a>`);
	}

	removeInitFileBtn.on("click", e => {
		e.preventDefault();
		removeInitFile = true;

		removeInitFileBtn.css("display", "none");
		chooseFileBtn.css("display", "inline");
		displayText.html("No file chosen");
	});

	chooseFileBtn.on("click", e => {
		e.preventDefault();
		fileInput.click();
	});

	removeFileBtn.on("click", e => {
		e.preventDefault();
		$("#display-text").html("No file chosen");
		chooseFileBtn.css("display", "inline");
		removeFileBtn.css("display", "none");
		fileInput.val("");
	});

	fileInput.on("change", () => {
		const file = fileInput[0].files[0];

		if (!isEmpty(fileInput.val())) {
			$("#display-text").html(file.name);
			removeFileBtn.css("display", "inline");
			chooseFileBtn.css("display", "none");
		}
	});
}

function getCategoryList(initCategory = -1) {
	const ajaxContainer = $("#ajax-category");

	$.get("../../../utility/functions.php", {
		functionCall: "getCategoryList",
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "";

		for (let i in response) {
			if (response[i].categoryID === initCategory) 
				content += `<option value="${response[i].categoryID}" selected>${response[i].categoryName}</option>`;
			else
				content += `<option value="${response[i].categoryID}">${response[i].categoryName}</option>`;
		}

		ajaxContainer.html(content);
	});
}

function updateSpecifiedProgramPage() {
	const pageID = parseInt($("#page-id").val());
	const categoryID = parseInt($("#ajax-category").val());
	const programName = $("#program-name").val();
	const cost = parseInt($("#cost").val());
	const programDuration = parseInt($("#program-duration").val());
	const focusProblemDesc = $("#focus-problem-desc").html();
	const about = $("#about").html();
	const expectedOutcome = $("#expected-outcome").html();

	// used to check it there is a previously uploaded file
	let initFilePath = $("#init-file-path").val();

	if (removeInitFile) {
		$.post("../../../utility/functions.php", {
			functionCall: "deleteSpecifiedMentoringProposalFile",
			pageID: pageID,
			filePath: initFilePath
		}, data => {
			// console.log(data);
			const response = JSON.parse(data);
			if (response.status != "success") {
				alert(response.message);
				return;
			}
		});

		// if file deletion succeed then change the file path content to ""
		initFilePath = "";
	}

	const file = $("#file")[0].files[0];

	// show error message if there is an empty field
	if (isEmpty(programName) || isEmpty(cost) || isEmpty(programDuration) || isEmpty(focusProblemDesc) || isEmpty(about) || isEmpty(expectedOutcome)) {
		alert("Unable to update page: please fill all required fields");
		return;
	}

	if (!isEmpty(file)) {
		let formData = new FormData();

		formData.append("functionCall", "updateSpecifiedProgramPage");
		formData.append("pageID", pageID);
		formData.append("categoryID", categoryID);
		formData.append("programName", programName);
		formData.append("cost", cost);
		formData.append("programDuration", programDuration);
		formData.append("focusProblemDesc", focusProblemDesc);
		formData.append("about", about);
		formData.append("expectedOutcome", expectedOutcome);
		formData.append("initFilePath", initFilePath);
		formData.append("file", file);

		$.ajax({
			url: "../../../utility/functions.php",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: data => {
				// console.log(data);
				const response = JSON.parse(data);
				if (response.status == "success")
					window.location = `specified_page.php?pageID=${pageID}&pageTitle=${programName}`;
				else 
					alert(response.message);
			}
		});

	} else {

		$.post("../../../utility/functions.php", {
			functionCall: "updateSpecifiedProgramPage",
			pageID: pageID,
			categoryID: categoryID,
			programName: programName,
			cost: cost,
			programDuration: programDuration,
			focusProblemDesc: focusProblemDesc,
			about: about,
			expectedOutcome: expectedOutcome,
			initFilePath: initFilePath
		}, data => {
			// console.log(data);
			const response = JSON.parse(data);
			if (response.status == "success")
				window.location = `specified_page.php?pageID=${pageID}&pageTitle=${programName}`;
			else 
				alert(response.message);
		});
	}
}