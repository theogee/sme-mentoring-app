// to show if user remove initial file
let removeInitFile = false;

$(document).ready(() => {
	let removeInitFile = { value: false };
	// console.log("hello world");
	getBusinessPage();

	$("#ajax-province").on("change", getCityList);

	// $("#update-btn").off();
	$("#update-btn").on("click", e => {
		e.preventDefault();
		updateBusinessPage();
	});

	$("#logout-btn").on("click", logout);

});

function setupFileUploadBtnWithInit() {
	const initFilePath = $("#init-file-path").val();
	const businessName = $("#ajax-business-name").val();
	const displayText = $("#display-text");

	const chooseFileBtn = $("#choose-file-btn");
	const removeFileBtn = $("#remove-file-btn");

	const removeInitFileBtn = $("#remove-init-file-btn");

	const fileInput = $("#file");

	if (!isEmpty(initFilePath)) {
		removeInitFileBtn.css("display", "inline");
		chooseFileBtn.css("display", "none");
		displayText.html(`<a href='../../../${initFilePath}' download='${businessName}'>business-proposal</a>`);
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

function getBusinessPage() {
	const pageID = parseInt($("#page-id").val());

	// section: edit-business-info
	const businessName = $("#ajax-business-name");
	const shortProblemDesc = $("#ajax-short-problem-desc");
	// section: edit-business-problem
	const longProblemDesc = $("#ajax-long-problem-desc");
	//section: edit-business-desc
	const about = $("#ajax-about");

	// console.log(pageID);
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);
		// console.log(response);

		// fill in initial value to the fields
		// section: edit-business-info
		businessName.val(response.businessName);
		getCategoryList(response.categoryID);
		$(`#view-level option[value='${response.viewLevel}']`).prop("selected", true);
		getProvinceList(response.provinceID, response.cityID);
		shortProblemDesc.html(response.shortProblemDesc);

		// section: edit-business-problem
		longProblemDesc.html(response.longProblemDesc);

		// section: edit-business-desc
		about.html(response.about);
		$("#init-file-path").attr("value", response.filePath);

		setupFileUploadBtnWithInit();
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

function getProvinceList(initProvince = -1, initCity = -1) {
	const ajaxContainer = $("#ajax-province");

	$.get("../../../utility/functions.php", {
		functionCall: "getProvinceList",
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "";

		for (let i in response) {
			if (response[i].provinceID === initProvince)
				content += `<option value="${response[i].provinceID}" selected>${response[i].provinceName}</option>`;
			else
				content += `<option value="${response[i].provinceID}">${response[i].provinceName}</option>`;
		}

		ajaxContainer.html(content);

		getCityList(initCity);
	});
}

function getCityList(initCity = -1) {
	const selectedProvince = $("#ajax-province").val();
	const ajaxContainer = $("#ajax-city");

	// console.log(selectedProvince);
	$.get("../../../utility/functions.php", {
		functionCall: "getCityList",
		provinceID: selectedProvince
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "";

		for (let i in response) {
			if (response[i].cityID === initCity)
				content += `<option value="${response[i].cityID}" selected>${response[i].cityName}</option>`;
			else 
				content += `<option value="${response[i].cityID}">${response[i].cityName}</option>`;
		}

		ajaxContainer.html(content);
	});
}

function updateBusinessPage() {
	const pageID = parseInt($("#page-id").val());
	const categoryID = parseInt($("#ajax-category").val());
	const cityID = parseInt($("#ajax-city").val());
	const businessName = $("#ajax-business-name").val();
	const shortProblemDesc = $("#ajax-short-problem-desc").html();
	const longProblemDesc = $("#ajax-long-problem-desc").html();
	const about = $("#ajax-about").html();
	const viewLevel = $("#view-level").val();

	// used to check if there is a previously uploaded file
	let initFilePath = $("#init-file-path").val();

	if (removeInitFile) {
		$.post("../../../utility/functions.php", {
			functionCall: "deleteBusinessProposalFile",
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
	if (isEmpty(businessName) || isEmpty(shortProblemDesc) || isEmpty(longProblemDesc) || isEmpty(about)) {
		alert("Please fill all required fields");
		return;
	}

	if (!isEmpty(file)) {
		let formData = new FormData();

		formData.append("functionCall", "updateBusinessPage");
		formData.append("pageID", pageID);
		formData.append("categoryID", categoryID);
		formData.append("cityID", cityID);
		formData.append("businessName", businessName);
		formData.append("shortProblemDesc", shortProblemDesc);
		formData.append("longProblemDesc", longProblemDesc);
		formData.append("about", about);
		formData.append("viewLevel", viewLevel);
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
					window.location = `business_page.php?pageID=${pageID}&pageTitle=${businessName}`;
				else 
					alert(response.message);
			}
		});

	} else {

		$.post("../../../utility/functions.php", {
			functionCall: "updateBusinessPage",
			pageID: pageID,
			categoryID: categoryID,
			cityID: cityID,
			businessName: businessName,
			shortProblemDesc: shortProblemDesc,
			longProblemDesc: longProblemDesc,
			about: about,
			initFilePath: initFilePath,
			viewLevel: viewLevel
		}, data => {
			// console.log(data);
			const response = JSON.parse(data);
			if (response.status == "success")
				window.location = `business_page.php?pageID=${pageID}&pageTitle=${businessName}`;
			else 
				alert(response.message);
		});
	}

}