<?php
session_start();

//Check if user logged in
if (!isset($_SESSION["userId"]) || !$_SESSION["userId"]) {
    header("Location: /comp3340-project/public_html/add_discussion.php");
    exit();
}

$userId = $_SESSION['userId'];

//Validate content
if (!isset($_POST['content']) || $_POST['content'] == '') {
    header("Location: /comp3340-project/public_html/add_discussion.php");
    exit();
}

//Validate title
if (!isset($_POST['title']) || strlen($_POST['title']) < 4 || strlen($_POST['title']) > 200) {
    header("Location: /comp3340-project/public_html/add_discussion.php");
    exit();
}

$title = $_POST['title'];

require 'dbConnection.php';

$content = $_POST['content'];

//Insert discussion into db
$preparedStatement = $conn->prepare('INSERT INTO discussion (authorId, title, content) VALUES (?, ?, ?)');
$preparedStatement->bind_param('iss', $userId, $title, $content);
$preparedStatement->execute();

header("Location: /comp3340-project/public_html/all_discussions.php");
exit();

?>