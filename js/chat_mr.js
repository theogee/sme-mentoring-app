const roomID = $("body").attr("data-room-id");
const senderInfo = $("body").attr("data-sender-info").split("-");
const userType = senderInfo[0];
const senderID = senderInfo[1];
const senderName = senderInfo[2];
const recepientID = $("body").attr("data-recepient-info");
const scope = $("body").attr("data-scope");

$(document).ready(() => {
	var conn = new WebSocket('ws://localhost:8080');
	conn.onopen = function(e) {
	    console.log("Connection established!");
	};

	gotoBottom("#chat-box");

	conn.onmessage = function(e) {
		const data = JSON.parse(e.data);
		if (data.scope == scope) {
			if (data.roomID == roomID) {
		    	// console.log(e.data);
		    	let content = "";
		    	if (data.senderID == senderID) {
		    		content = `
		    		<div class="self chat-container">
						<div class="chat-info">
							<span>You</span>
						</div>
						<div class="self chat-bubble">
							<span class="chat-dt">${data.dt}</span>
							<span class="chat-msg">${data.msg}</span>
						</div>
					</div>`;
		    	} else {
		    		content = `
		    		<div class="chat-container">
						<div class="chat-info">
							<span>${data.senderName}</span>
						</div>
						<div class="chat-bubble">
							<span class="chat-msg">${data.msg}</span>
							<span class="chat-dt">${data.dt}</span>
						</div>
					</div>`;
		    	}

		    	$("#chat-box").append(content);
		    	gotoBottom("#chat-box");
		    }
		}
	};
	
	$("#send-btn").off();
	$("#send-btn").on("click", () => {
		const msg = $("#text-box").html();
			if (msg != "") {
				const data = {
				scope: scope,
				roomID: roomID,
				userType: userType,
				senderID: senderID,
				senderName: senderName,
				recepientID: recepientID,
				msg: msg
			};
			$("#text-box").html("");
			conn.send(JSON.stringify(data));			
		}						
	});
});

function gotoBottom(element){
   var element = document.querySelector(element);
   element.scrollTop = element.scrollHeight - element.clientHeight;
}