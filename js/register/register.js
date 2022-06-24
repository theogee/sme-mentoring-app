const ownerAjaxProvince = $("#owner-ajax-province");
const expertAjaxProvince = $("#expert-ajax-province");

$(document).ready(() => {
	getProvinceList();

	$("input[name='user-type']").on("change", () => {
		const registerAs = $("input[name='user-type']:checked").val();
		if (registerAs == "owner") {
			$("#register-as-owner").css("display", "block");
			$("#register-as-expert").css("display", "none");
		} else {
			$("#register-as-expert").css("display", "block");
			$("#register-as-owner").css("display", "none");
		}
	});

	ownerAjaxProvince.on("change", () => {
		getCityList("owner");
	});
	expertAjaxProvince.on("change", () => {
		getCityList("expert");
	});
});

function isEmpty(value) {
	return (value == '' || value == null) ? true : false;
}

function getProvinceList() {
	$.get("../utility/functions.php", {
		functionCall: "getProvinceList",
	}, data => {
		const response = JSON.parse(data);
		// console.log(response);
		let content = "<option value='' disabled selected hidden>please choose...</option>";

		for (let i in response)
			content += `<option value="${response[i].provinceID}">${response[i].provinceName}</option>`;

		ownerAjaxProvince.html(content);
		expertAjaxProvince.html(content);
	});
}

function getCityList(userType) {
	let selectedProvince;
	let ajaxContainer;

	if (userType == "owner") {
		selectedProvince = $("#owner-ajax-province").val();
		ajaxContainer = $("#owner-ajax-city");
	} else {
		selectedProvince = $("#expert-ajax-province").val();
		ajaxContainer = $("#expert-ajax-city");
	}

	if (isEmpty(selectedProvince))
		return;

	ajaxContainer.prop("disabled", false);

	// console.log(selectedProvince);
	$.get("../utility/functions.php", {
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