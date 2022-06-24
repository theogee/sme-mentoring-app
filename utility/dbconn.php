<?php 
	function connect() {
		$servername = "localhost";
		$dbuser = "root";
		$dbpassword = "";
		$dbname = "sme-mentoring-app";

		$dbconn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

		if ($dbconn->connect_error) {
			die("Connection error to database: $dbconn->connect_error");
		}

		return $dbconn;
	}
 ?>