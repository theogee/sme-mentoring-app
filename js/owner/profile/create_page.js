$(document).ready(() => {
	// console.log("hello world");

	getCategoryList();
	getProvinceList();

	setupFileUploadBtn();
	
	$("#ajax-province").on("change", getCityList);

	$("#create-btn").on("click", e => {
		e.preventDefault();
		createBusinessPage();
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

function getProvinceList() {
	const ajaxContainer = $("#ajax-province");

	$.get("../../../utility/functions.php", {
		functionCall: "getProvinceList",
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "<option value='' disabled selected hidden>please choose...</option>";

		for (let i in response)
			content += `<option value="${response[i].provinceID}">${response[i].provinceName}</option>`;

		ajaxContainer.html(content);

		getCityList();
	});
}

function getCityList() {
	const selectedProvince = $("#ajax-province").val();

	if (isEmpty(selectedProvince))
		return;

	const ajaxContainer = $("#ajax-city");

	ajaxContainer.prop("disabled", false);

	// console.log(selectedProvince);
	$.get("../../../utility/functions.php", {
		functionCall: "getCityList",
		provinceID: selectedProvince
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "<option value='' disabled selected hidden>please choose...</option>";

		for (let i in response)
			content += `<option value="${response[i].cityID}">${response[i].cityName}</option>`;

		ajaxContainer.html(content);
	});
}

function createBusinessPage() {
	const ownerID = parseInt($("#owner-id").val());
	const categoryID = parseInt($("#ajax-category").val());
	const cityID = parseInt($("#ajax-city").val());
	const businessName = $("#business-name").val();
	const shortProblemDesc = $("#short-problem-desc").html();
	const longProblemDesc = $("#long-problem-desc").html();
	const about = $("#about").html();
	const viewLevel = $("#view-level").val();

	const file = $("#file")[0].files[0];

	// show error message if there is an empty field
	if (isEmpty(categoryID) || isEmpty(cityID) || isEmpty(businessName) || isEmpty(shortProblemDesc) || isEmpty(longProblemDesc) || isEmpty(about)) {
		alert("Unable to create page: please fill all required fields");
		return;
	}

	if (!isEmpty(file)) {
		let formData = new FormData();

		formData.append("functionCall", "createBusinessPage");
		formData.append("ownerID", ownerID);
		formData.append("categoryID", categoryID);
		formData.append("cityID", cityID);
		formData.append("businessName", businessName);
		formData.append("shortProblemDesc", shortProblemDesc);
		formData.append("longProblemDesc", longProblemDesc);
		formData.append("about", about);
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
			functionCall: "createBusinessPage",
			ownerID: ownerID,
			categoryID: categoryID,
			cityID: cityID,
			businessName: businessName,
			shortProblemDesc: shortProblemDesc,
			longProblemDesc: longProblemDesc,
			about: about,
			viewLevel: viewLevel
		}, data => {
			// console.log(data);
			window.location = "profile.php";
		});
	}
}