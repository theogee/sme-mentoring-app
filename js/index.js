const loginbtn = document.getElementById("login-btn");
const registerbtn = document.getElementById("register-btn");

loginbtn.addEventListener("click", () => {
	window.location = "page/login.html";
});

registerbtn.addEventListener("click", () => {
	window.location = "page/register.html";
});