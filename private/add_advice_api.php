<?php
session_start();

//redirect if not logged in
if (!isset($_SESSION["userId"]) || !$_SESSION["userId"]) {
    header("Location: /comp3340-project/public_html/add_discussion.php");
    exit();
}

$userId = $_SESSION['userId'];

//validate content
if (!isset($_POST['content']) || $_POST['content'] == '') {
    header("Location: /comp3340-project/public_html/add_discussion.php");
    exit();
}

//validate the title
if (!isset($_POST['title']) || strlen($_POST['title']) < 4 || strlen($_POST['title']) > 200) {
    header("Location: /comp3340-project/public_html/add_discussion.php");
    exit();
}

$title = $_POST['title'];

require 'dbConnection.php';

$content = $_POST['content'];
$summary = $_POST['summary'];
$imageLink = $_POST['imageLink'];

//insert into db
$preparedStatement = $conn->prepare('INSERT INTO advice (authorId, title, imageLink, summary, content) VALUES (?, ?, ?, ?, ?)');
$preparedStatement->bind_param('issss', $userId, $title, $imageLink, $summary, $content);
$preparedStatement->execute();

//redirect after insert
header("Location: /comp3340-project/public_html/all_advice.php");
exit();

?>