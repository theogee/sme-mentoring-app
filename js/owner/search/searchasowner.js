$(document).ready(() => {
	getProvinceList();
	$("#ajax-province").on("change", getCityList);
	$("#logout-btn").on("click", logout);
});

function isEmpty(value) {
	return (value == '' || value == null) ? true : false;
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

function logout() {
	$.post("../../../utility/functions.php", {
		functionCall: "logoutOwner"
	}, () => {
		window.location = "http://localhost/umkm_project/";
	});
}