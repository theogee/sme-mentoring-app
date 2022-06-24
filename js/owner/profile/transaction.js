const requestID = $("body").attr("data-request-id");
const req = $("body").attr("data-req");

$(document).ready(() => {
	if (req == "ir")
		getInterestRequestPage();
	else
		getOfferRequestPage();

	setupFileUploadBtn();

	$("#upload-btn").on("click", uploadPOT);
});

function getOfferRequestPage() {
	$.get("../../../utility/functions.php", {
		functionCall: "getOfferRequestPage",
		requestID: requestID
	}, data => {
		const response = JSON.parse(data);

		if (response.status != "success") {
			window.location = "profile.php";
		}
		// POT interface control
		if (response.transactionStatus == "rejected") {
			$("#ajax-notif").css("display", "block");
			$("#ajax-notif").html("<p>NOTIFICATION: Transaction has been rejected. Please check your email or contact support@iqoniq.com for further information</p>");
		} else if (response.potPath != null) {
			$("#ajax-notif").css("display", "block");
			$("#pot-interface").remove();
			$("#ajax-notif").html("<p>NOTIFICATION: Proof of transaction has been uploaded. Waiting for admin verification to establish mentor room</p>");
		} else {
			$("#pot-interface").css("display", "block");
		}

		getBusinessPage(response.businessPageID);
		getSpecifiedProgramPage(response.specifiedPageID);
	});
}

function getInterestRequestPage() {

	$.get("../../../utility/functions.php", {
		functionCall: "getInterestRequestPage",
		requestID: requestID
	}, data => {
		// console.log(data);
		const response = JSON.parse(data);

		if (response.status != "success") {
			window.location = "profile.php";
		}
		// POT interface control
		if (response.transactionStatus == "rejected") {
			$("#ajax-notif").css("display", "block");
			$("#ajax-notif").html("<p>NOTIFICATION: Transaction has been rejected. Please check your email or contact support@iqoniq.com for further information</p>");
		} else if (response.potPath != null) {
			$("#ajax-notif").css("display", "block");
			$("#pot-interface").remove();
			$("#ajax-notif").html("<p>NOTIFICATION: Proof of transaction has been uploaded. Waiting for admin verification to establish mentor room</p>");
		} else {
			$("#pot-interface").css("display", "block");
		}
		
		getBusinessPage(response.businessPageID);
		getSpecifiedProgramPage(response.specifiedPageID);
	}); 
}

function getBusinessPage(pageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getBusinessPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);
		const content = `
		<div>
			<p class="business-name">${response.businessName} <span class="business-category">${response.categoryName}</span> <span class="business-viewlevel">${response.viewLevel}</span></p>
			<p>Problem:<br>${response.shortProblemDesc}</p>
			<p>Owned By: ${response.ownerName}</p>
			<a class="business-page-link" href="business_page.php?pageID=${response.pageID}&pageTitle=${response.businessName}">Full Page</a>
		</div>`;

		$("#ajax-business-page").html(content);
	});
}

function getSpecifiedProgramPage(pageID) {
	$.get("../../../utility/functions.php", {
		functionCall: "getSpecifiedProgramPage",
		pageID: pageID
	}, data => {
		const response = JSON.parse(data);

		let content = `
		<p>Total: Rp. ${parseInt(response.cost).toLocaleString()}</p>
		<p>Duration: ${response.programDuration} months</p>`;

		$("#ajax-receipt").html(content);

		content = `
		<div>
			<p class="program-name">${response.programName} <span class="program-category">${response.categoryName}</span> <span class="specified-tag">specified</span></p>
			<p>Focus Problem:<br>${response.focusProblemDesc}</p>
			<p>Created By: ${response.expertName}</p>
			<a class="program-page-link" href="../search/program_page.php?pageID=${response.pageID}&pageTitle=${response.programName}&type=specified">Full Page</a>
		</div>`;

		$("#ajax-specified-page").html(content);
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
			$("#upload-btn").prop("disabled", false);
		}
	});

	if (isEmpty(fileInput.val()))
		$("#upload-btn").prop("disabled", true);
}

function uploadPOT() {
	if (isEmpty($("#file").val())) {
		alert("can't upload: file is empty");
		return;
	}

	const file = $("#file")[0].files[0];
	
	let formData = new FormData();
	formData.append("functionCall", "uploadPOT");
	formData.append("req", req);
	formData.append("requestID", requestID);
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
				if (response.status == "success") {
					$("#ajax-notif").css("display", "block");
					$("#ajax-notif").html("<p>NOTIFICATION: Proof of transaction has been uploaded. Waiting for admin verification to establish mentor room</p>");
					$("#pot-interface").remove();
				}
				else {
					alert(response.message);
				}
			}
		});
}