$(document).ready(() => {
	const initProvince = $("#ajax-province").attr("data-init-province-id");
	const initCity = $("#ajax-city").attr("data-init-city-id");

	// console.log("hello world");
	getProvinceList(initProvince, initCity);
	$("#ajax-province").on("change", getCityList);

	$("#show-pwd").on("click", e => {
		e.preventDefault();
		if ($("#expert-password").attr("type") === "password")
			$("#expert-password").attr("type", "text");
		else
			$("#expert-password").attr("type", "password");
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