<?php

require 'dbConnection.php';

$username = $_POST['username'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$bio = $_POST['bio'];

if(empty($username)) {
    header("Location: /comp3340-project/signup.php?error=username");
    exit();
}

if (empty($username)) {
    header("Location: /comp3340-project/signup.php?error=password");
    exit();
}

if (strlen($username) >= 4 && strlen($username) <= 50) {
    header("Location: /comp3340-project/signup.php?error=usernameLength");
    exit();
}

if (strlen($password) >= 8 && strlen($password) <= 100) {
    header("Location: /comp3340-project/signup.php?error=passwordLength");
    exit();
}

if (strlen($bio) <= 500) {
    header("Location: /comp3340-project/signup.php?error=bio");
    exit();
}

if ($password != $confirmPassword) {
    header("Location: /comp3340-project/signup.php?error=passwordMatch");
    exit();
}

$query = "SELECT * FROM users WHERE username = ?";
$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("s", $username);
$preparedStatement->execute();

$result = $preparedStatement->get_result();

if( $result->num_rows > 0) {
    header("Location: /comp3340-project/signup.php?error=exists");
    exit();
}
else {

    $query = "INSERT INTO users (username, password, bio) VALUES (?, ?, ?)";
    $preparedStatement = $conn->prepare($query);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $preparedStatement->bind_param("sss", $username, $hashedPassword, $bio);
    $preparedStatement->execute();

    header('Location: /comp3340-project/login.php');
    exit();
}

?>