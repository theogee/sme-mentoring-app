<?php

require_once "../../utility/dbconn.php";
$dbconn = connect();

//Input validation
$name = $password = $age = $cityID = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["cityID"])){
        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $password = htmlspecialchars($_POST["password"]);
        $cityID = (int) $_POST["cityID"];

        $checkEmail = "SELECT email FROM business_owner WHERE email = '$email'";
        $result = $dbconn->query($checkEmail);
        if ($result->num_rows != 0)
            exit("An error occured: email has been registered, please input another one. <a href='../register.html'>Click Here!</a>");

    } else {
        die("An error occured: please fill all the required fields. <a href='../register.html'>Click Here!</a>");
    }
} else {
    header("location: ../register.html");
}

//Create a query
$query = "INSERT INTO business_owner VALUES (null , $cityID, '', '$name','$email', '$password')";

if($dbconn->query($query) === TRUE) {
    echo "You are now registered! <a href='../login.html'>Click here to login</a>";
} else {
    echo "Error: " . $query . "<br>" . $dbconn->error;
} 

mysqli_close($dbconn);

?>