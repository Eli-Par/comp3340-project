<?php

require_once 'dbConnection.php';

$username = $_POST['username'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$bio = $_POST['bio'];

//Check if there is any issues with the user input
if(empty($username)) {
    header("Location: /comp3340-project/public_html/signup.php?error=username");
    exit();
}

if (empty($username)) {
    header("Location: /comp3340-project/public_html/signup.php?error=password");
    exit();
}

if (strlen($username) < 4 || strlen($username) > 50) {
    header("Location: /comp3340-project/public_html/signup.php?error=usernameLength");
    exit();
}

if (strlen($password) < 8 || strlen($password) > 100) {
    header("Location: /comp3340-project/public_html/signup.php?error=passwordLength");
    exit();
}

if (strlen($bio) > 500) {
    header("Location: /comp3340-project/public_html/signup.php?error=bio");
    exit();
}

if ($password != $confirmPassword) {
    header("Location: /comp3340-project/public_html/signup.php?error=passwordMatch");
    exit();
}

//Check if there exists a user with that username
$query = "SELECT * FROM users WHERE username = ?";
$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("s", $username);
$preparedStatement->execute();

$result = $preparedStatement->get_result();

// If the username is taken redirect with an error
if( $result->num_rows > 0) {
    header("Location: /comp3340-project/public_html/signup.php?error=exists");
    exit();
}
else {
    //Insert the user into the database and redirect to the home page
    $query = "INSERT INTO users (username, password, bio) VALUES (?, ?, ?)";
    $preparedStatement = $conn->prepare($query);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $preparedStatement->bind_param("sss", $username, $hashedPassword, $bio);
    $preparedStatement->execute();

    header('Location: /comp3340-project/public_html/login.php');
    exit();
}

?>