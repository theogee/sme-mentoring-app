$(document).ready(() => {
	// console.log("hello world");
	getOwnerInfo();

	getProvinceList();
	$("#ajax-province").on("change", getCityList);

	$("#update-btn").on("click", e => {
		e.preventDefault();
		updateOwner();
	});

	$("#logout-btn").on("click", logout);

	$("#show-pwd").on("click", e => {
		e.preventDefault();
		if ($("#ajax-owner-password").attr("type") === "password")
			$("#ajax-owner-password").attr("type", "text");
		else
			$("#ajax-owner-password").attr("type", "password");
	});

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

function getOwnerInfo() {
	const ownerID = parseInt($("#owner-id").val());

	// console.log(ownerID);
	$.get("../../../utility/functions.php", {
		functionCall: "getOwnerInfo",
		ownerID: ownerID
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);

		$("#ajax-owner-name").val(response.ownerName);
		$("#ajax-owner-email").val(response.ownerEmail);
		$("#ajax-owner-password").val(response.ownerPassword);

		getProvinceList(response.provinceID, response.cityID);
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

function updateOwner() {
	const ownerID = parseInt($("#owner-id").val());
	const cityID = parseInt($("#ajax-city").val());
	const ownerName = $("#ajax-owner-name").val();
	const ownerEmail = $("#ajax-owner-email").val();
	const ownerPassword = $("#ajax-owner-password").val();

	// show error message if there is an empty field
	if (isEmpty(ownerName) || isEmpty(ownerEmail) || isEmpty(ownerPassword)) {
		alert("Please fill all required fields");
		return;
	}

	$.post("../../../utility/functions.php", {
		functionCall: "updateOwner",
		ownerID: ownerID,
		cityID: cityID,
		ownerName: ownerName,
		ownerEmail: ownerEmail,
		ownerPassword: ownerPassword
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);

		alert(response.message);
	});
}