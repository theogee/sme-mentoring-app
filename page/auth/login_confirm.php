<?php

session_start();

require_once "../../utility/dbconn.php";
$dbconn = connect();

// echo "Connected to database successfully <br><br>";

//Input Validation
$email = $password = " ";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["email"]) && !empty($_POST["password"])) {
        $email = htmlspecialchars($_POST["email"]);
        $password =  htmlspecialchars($_POST["password"]);
    } else {
        die("Please input both email & password. <a href='../login.html'>Click Here!</a>");
    }
} else {
    die("Please go to login form first. <a href='../login.html'>Click Here!</a>");
}

if ($_POST["login-as"] == "business-owner") {
    //Create a query
    $query = "SELECT * FROM business_owner WHERE email = '$email' AND  password = '$password'";

    //Create a variable to contain the results
    $result = $dbconn->query($query);
    $row = $result->fetch_assoc();

    //Check and display the results
    if(empty($email)) {
        die("<p style='color:#dc2f02'>Email can't be empty.<p>");
    } else if(empty($password)) {
        die("<p style='color:#dc2f02'>Password can't be empty.<p>");
    } else if ($result->num_rows == 0) {
        exit("Email or password is wrong, please try again. <a href='../login.html'>Click Here!</a>");
    } else if($result->num_rows > 0) {
        $_SESSION["ownerID"] = $row["id"];
        header("location: ../owner/profile/profile.php");
    }
} else {
    //Create a query
    $query = "SELECT * FROM expert WHERE email = '$email' AND  password = '$password'";

    //Create a variable to contain the results
    $result = $dbconn->query($query);
    $row = $result->fetch_assoc();

    //Check and display the results
    if(empty($email)) {
        die("<p style='color:#dc2f02'>Email can't be empty.<p>");
    } else if(empty($password)) {
        die("<p style='color:#dc2f02'>Password can't be empty.<p>");
    } else if ($result->num_rows == 0) {
        exit("Email or password is wrong, please try again. <a href='../login.html'>Click Here!</a>");
    } else if($result->num_rows > 0) {
        $_SESSION["expertID"] = $row["id"];
        header("location: ../expert/profile/profile.php");
    }
}

mysqli_close($dbconn);