<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="advice_grid.css" />
    <link rel="stylesheet" href="advice_interaction.css" />
    

    <script src="advice_interactions.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<?php
require_once '../private/advice_grid.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;

//Get all advice entries with like counts and if the user liked or disliked
$query = "SELECT 
    adviceId, 
    title, 
    content, 
    username,
    dateCreated,

    -- Like and dislike count queries
    (SELECT COUNT(*) FROM advice_interactions a1 WHERE a1.adviceId = a.adviceId AND a1.isLike = 1) AS likeCount,
    (SELECT COUNT(*) FROM advice_interactions a2 WHERE a2.adviceId = a.adviceId AND a2.isLike = 0) AS dislikeCount,
    
    -- Booleans for current user
    EXISTS (
        SELECT 1 FROM advice_interactions a3
        WHERE a3.adviceId = a.adviceId AND a3.userId = ? AND a3.isLike = 1
    ) AS likedByUser,
    EXISTS (
        SELECT 1 FROM advice_interactions a4
        WHERE a4.adviceId = a.adviceId AND a4.userId = ? AND a4.isLike = 0
    ) AS dislikedByUser
FROM advice a JOIN users ON a.authorId = users.userId
WHERE EXISTS (
        SELECT 1 FROM advice_interactions a3
        WHERE a3.adviceId = a.adviceId AND a3.userId = ? AND a3.isLike = 1
    )";
$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("iii", $userId, $userId, $userId);
$preparedStatement->execute();
$result = $preparedStatement->get_result();
?>

<main>
<?php if ($userId != 0) { ?>
    <h1 style="margin-bottom: 10px;">Advice You Liked</h1>
     createAdviceGrid($result); <?php
} else { ?>
    <section class="card">
        <h2>Advice You Liked</h2>
        <p style="text-align: center; margin-top: 10px;">You need to be logged in to see your liked advice<br/><a href="login.php">Login here!</a></p>
        
    </section>
<?php } ?>
</main>

<?php include '../private/partial/footer.php'; ?>