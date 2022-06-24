$(document).ready(() => {
	const programType = $("#program-type");
	const generalCost = $("#general-cost");
	const specifiedCost = $("#specified-cost");
	// console.log("hello world");
	getCategoryList();

	setupFileUploadBtn();

	$("#create-btn").on("click", e => {
		e.preventDefault();
		createProgramPage();
	});

	// if program type is specified, view level only private
	programType.on("change", () => {
		if (programType.val() == "specified") {
			$("#view-level").prop("disabled", true);
			$("#view-level option[value='private']").prop("selected", true);

			generalCost.css("display", "none");
			specifiedCost.css("display", "block");

		} else {
			$("#view-level").prop("disabled", false);
			$("#view-level option[value='private']").prop("selected", false);

			generalCost.css("display", "block");
			specifiedCost.css("display", "none");
		}
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
			$("#file-name").html(file.name);
			removeFileBtn.css("display", "inline");
			chooseFileBtn.css("display", "none");
		}
	});
}

function getCategoryList() {
	const ajaxContainer = $("#ajax-category");

	$.get("../../../utility/functions.php", {
		functionCall: "getCategoryList",
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "<option value='' disabled selected hidden>please choose...</option>";

		for (let i in response)
			content += `<option value="${response[i].categoryID}">${response[i].categoryName}</option>`;

		ajaxContainer.html(content);
	});
}

function createProgramPage() {
	const programType = $("#program-type").val();
	const expertID = parseInt($("#expert-id").val());
	const categoryID = parseInt($("#ajax-category").val());
	const programName = $("#program-name").val();
	const programDuration = parseInt($("#program-duration").val());
	const focusProblemDesc = $("#focus-problem-desc").html();
	const about = $("#about").html();
	const expectedOutcome = $("#expected-outcome").html();

	const file = $("#file")[0].files[0];

	// different value based on program type
	let minCost = "not available";
	let maxCost = "not available";
	let cost = "not available";
	let viewLevel = "not available";

	if (programType == "general") {
		minCost = parseInt($("#min-cost").val());
		maxCost = parseInt($("#max-cost").val());
		viewLevel = $("#view-level").val();
	} else {
		cost = parseInt($("#cost").val());
	}

	// show error message if there is an empty field
	if (isEmpty(categoryID) || isEmpty(programName) || isEmpty(minCost) || isEmpty(maxCost) || isEmpty(programDuration) || isEmpty(focusProblemDesc) || isEmpty(about) || isEmpty(expectedOutcome)) {
		alert("Unable to create page: please fill all required fields");
		return;
	}

	if (!isEmpty(file)) {
		let formData = new FormData();

		formData.append("functionCall", "createProgramPage");
		formData.append("programType", programType);
		formData.append("expertID", expertID);
		formData.append("categoryID", categoryID);
		formData.append("programName", programName);
		formData.append("minCost", minCost);
		formData.append("maxCost", maxCost);
		formData.append("cost", cost);
		formData.append("programDuration", programDuration);
		formData.append("focusProblemDesc", focusProblemDesc);
		formData.append("about", about);
		formData.append("expectedOutcome", expectedOutcome);
		formData.append("viewLevel", viewLevel);
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
					window.location = "profile.php";
				else 
					alert(response.message);
			}
		});

	} else {

		$.post("../../../utility/functions.php", {
			functionCall: "createProgramPage",
			programType: programType,
			expertID: expertID,
			categoryID: categoryID,
			programName: programName,
			minCost: minCost,
			maxCost: maxCost,
			cost: cost,
			programDuration: programDuration,
			focusProblemDesc: focusProblemDesc,
			about: about,
			expectedOutcome: expectedOutcome,
			viewLevel: viewLevel
		}, data => {
			// console.log(data);
			const response = JSON.parse(data);
			if (response.status == "success")
				window.location = "profile.php";
			else 
				alert(response.message);
		});
	}
}