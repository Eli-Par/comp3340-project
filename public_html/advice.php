<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="interactions.css" />
    

    <script src="advice_interactions.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<?php
require_once '../private/advice_card.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$adviceId = $_GET['adviceId'] ?? 0;

//Get the advices details
$query = "SELECT 
    adviceId, 
    title, 
    content, 
    username,
    bio,
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
FROM advice a JOIN users ON a.authorId = users.userId WHERE a.adviceId=?";

$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("iii", $userId, $userId, $adviceId);
$preparedStatement->execute();

$result = $preparedStatement->get_result();

//If the advice exists get all its properties, otherwise set defaults
if ($result->num_rows > 0) {
    $advice = $result->fetch_assoc();

    $title = $advice["title"] ?? "";
    $content = $advice["content"] ?? "";
    $username = $advice["username"] ?? "";
    $likes = $advice["likeCount"];
    $dislikes = $advice["dislikeCount"];
    $isLiked = $advice["likedByUser"];
    $isDisliked = $advice["dislikedByUser"];
    $bio = $advice["bio"];

    if ($userId) {
        $sql = "INSERT INTO advice_history (userId, adviceId) VALUES (?, ?)";
        $statement = $conn->prepare($sql);
        $statement->bind_param("ii", $userId, $adviceId);
        $statement->execute();
    }
}
else {
    $title = "Advice not found";
    $content = "";
    $username = "Unknown";
    $likes = 0;
    $dislikes = 0;
    $isLiked = 0;
    $isDisliked = 0;
    $bio = "";
}

?>

<main>
    <section class="card" style="max-width: 90vw; margin: 0 auto;">
        <h2><?php echo htmlentities($title) ?></h2>
        <h3 style="text-align: center;">By <?php echo htmlentities(string: $username) ?></h3>
        <div style="display: flex; justify-content: center;">
            <?php echo createAdviceInteractionButtons($adviceId, $likes, $dislikes, $isLiked, $isDisliked, true) ?>
        </div>

        <p style="text-align: center; margin-top: 10px;">
            <?php echo nl2br(htmlspecialchars($content)) ?>
        </p>
    </section>
    <section class="card" style="max-width: calc(min(600px, 90vw)); margin: 20px auto;">
        <h2>About <?php echo htmlentities($username) ?></h2>
        <p style="text-align: center; margin-top: 10px;">
            <?php echo nl2br(htmlspecialchars($bio)) ?>
        </p>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>