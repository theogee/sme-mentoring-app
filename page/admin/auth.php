<?php 
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["username"]) && isset($_POST["password"])) {
			require_once "../../utility/dbconn.php";
			$dbconn = connect();
			$username = htmlspecialchars($_POST["username"]);
			$password = htmlspecialchars($_POST["password"]);

			$query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";

			$result = $dbconn->query($query);
			if ($result->num_rows != 1) {
				echo "<p>An error occured: username or password is wrong</p>";
			} else {
				session_start();
				$_SESSION["admin"] = "login";
				header("location: index.php");
			}
		} else {
			echo "<p>An error occured: username or password is missing</p>";
		}
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Login</title>
</head>
<body>
	<form action="auth.php" method="POST">
		<label for="username">username:</label>
		<input type="text" name="username" id="username">
		<br><br>
		<label for="password">password:</label>
		<input type="password" name="password" id="password">
		<br><br>
		<input type="submit" value="login">
	</form>
</body>
</html>