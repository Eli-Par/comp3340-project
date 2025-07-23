<?php
session_start();

// Check user logged in
if (!isset($_SESSION['userId'])) {
    http_response_code(401);
    echo 'Unauthorized';
    exit();
}

$userId = $_SESSION['userId'];

//Check it is a post request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit();
}

require '../private/dbConnection.php';

$bio = isset($_POST['bio']) ? $_POST['bio'] : '';

$username = isset($_POST['username']) ? $_POST['username'] : $_SESSION['username'];

//Check bio length
if (strlen($bio) > 500) {
    http_response_code(400);
    echo 'Bio too long, max 500 characters';
    exit();
}

//Check username length
if (strlen($username) > 50 || strlen($username) < 4) {
    http_response_code(400);
    echo 'Username not between 4 and 50 characters';
    exit();
}

//Update existing user entry
$statement = $conn->prepare("UPDATE users SET username = ?, bio = ? WHERE userId = ?");
$statement->bind_param("ssi", $username, $bio, $userId);

$statement->execute();

$_SESSION["username"] = $username;
$_SESSION["bio"] = $bio;

header("Location: /comp3340-project/public_html/my_profile.php");
exit();
