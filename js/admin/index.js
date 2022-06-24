$(document).ready(() => {
	getPendingTransactionFromInterestRequest();

	$("#logout-btn").on("click", () => {
		$.post("../../utility/functions.php", {
			functionCall: "logout"
		}, () => {
			window.location = "auth.php";
		});
	});

	// pending transaction
	$("#pending-transaction-link").on("click", () => {
		if (!$("#pending-transaction-link").hasClass("selected-link")) {
			$("#pending-transaction-link").addClass("selected-link");
			getPendingTransactionFromInterestRequest();
			$("#pending-transaction-option").css("display", "block");
			$("#rejected-transaction-link").removeClass("selected-link");
			$("#rejected-transaction-option").css("display", "none");
			// set initial view
			if (!$("#pt-ir-link").hasClass("selected-link")) {
				$("#pt-ir-link").addClass("selected-link");
				$("#pt-or-link").removeClass("selected-link");
			}
		}
	});

	$("#pt-ir-link").on("click", () => {
		if (!$("#pt-ir-link").hasClass("selected-link")) {
			getPendingTransactionFromInterestRequest();
			$("#pt-ir-link").addClass("selected-link");
			$("#pt-or-link").removeClass("selected-link");
		}
	});

	$("#pt-or-link").on("click", () => {
		if (!$("#pt-or-link").hasClass("selected-link")) {
			getPendingTransactionFromOfferRequest();
			$("#pt-or-link").addClass("selected-link");
			$("#pt-ir-link").removeClass("selected-link");
		}
	});

	// rejected transaction
	$("#rejected-transaction-link").on("click", () => {
		if (!$("#rejected-transaction-link").hasClass("selected-link")) {
			$("#rejected-transaction-link").addClass("selected-link");
			getRejectedTransactionFromInterestRequest();
			$("#rejected-transaction-option").css("display", "block");
			$("#pending-transaction-link").removeClass("selected-link");
			$("#pending-transaction-option").css("display", "none");
			// set initial view
			if (!$("#rt-ir-link").hasClass("selected-link")) {
				$("#rt-ir-link").addClass("selected-link");
				$("#rt-or-link").removeClass("selected-link");
			}
		}
	});

	$("#rt-ir-link").on("click", () => {
		if (!$("#rt-ir-link").hasClass("selected-link")) {
			getRejectedTransactionFromInterestRequest();
			$("#rt-ir-link").addClass("selected-link");
			$("#rt-or-link").removeClass("selected-link");
		}
	});

	$("#rt-or-link").on("click", () => {
		if (!$("#rt-or-link").hasClass("selected-link")) {
			getRejectedTransactionFromOfferRequest();
			$("#rt-or-link").addClass("selected-link");
			$("#rt-ir-link").removeClass("selected-link");
		}
	});

	
});


function getRejectedTransactionFromInterestRequest() {
	$.get("../../utility/functions.php", {
		functionCall: "getRejectedTransactionFromInterestRequest"
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		let content = `<table><tr><th>request ID</th><th>request type</th><th>link</th></tr>`;
		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<tr>
					<td>${response.list[i]}</td>
					<td>ir</td>
					<td><a href="resolve_transaction.php?requestID=${response.list[i]}&requestType=ir">detail</a></td>
				</tr>`;
			}

			$("#ajax-container").html(content + "</table>");
		} else {
			$("#ajax-container").html("<p>No rejected transaction</p>");
		}
	});
}

function getRejectedTransactionFromOfferRequest() {
	$.get("../../utility/functions.php", {
		functionCall: "getRejectedTransactionFromOfferRequest"
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		let content = `<table><tr><th>request ID</th><th>request type</th><th>link</th></tr>`;
		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<tr>
					<td>${response.list[i]}</td>
					<td>ir</td>
					<td><a href="resolve_transaction.php?requestID=${response.list[i]}&requestType=or">detail</a></td>
				</tr>`;
			}

			$("#ajax-container").html(content + "</table>");
		} else {
			$("#ajax-container").html("<p>No rejected transaction</p>");
		}
	});
}

function getPendingTransactionFromInterestRequest() {
	$.get("../../utility/functions.php", {
		functionCall: "getPendingTransactionFromInterestRequest"
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		let content = `<table><tr><th>request ID</th><th>request type</th><th>link</th></tr>`;
		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<tr data-request-type='ir'>
					<td>${response.list[i]}</td>
					<td>ir</td>
					<td><a href="transaction_detail.php?requestID=${response.list[i]}&requestType=ir">detail</a></td>
				</tr>`;
			}

			$("#ajax-container").html(content + "</table>");
		}  else {
			$("#ajax-container").html("<p>No pending transaction</p>");
		}
	});
}

function getPendingTransactionFromOfferRequest() {
	$.get("../../utility/functions.php", {
		functionCall: "getPendingTransactionFromOfferRequest"
	}, data => {
		const response = JSON.parse(data);

		let content = `<table><tr><th>request ID</th><th>request type</th><th>link</th></tr>`;
		if (response.status == "found") {
			for (let i in response.list) {
				content += `
				<tr data-request-type='or'>
					<td>${response.list[i]}</td>
					<td>or</td>
					<td><a href="transaction_detail.php?requestID=${response.list[i]}&requestType=or">detail</a></td>
				</tr>`;
			}

			$("#ajax-container").html(content + "</table>");
		} else {
			$("#ajax-container").html("<p>No pending transaction</p>");
		}
	});
}