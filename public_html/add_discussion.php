<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="add_discussion.css" />
</head>

<?php include '../private/partial/header.php'; ?>

<?php
$userId = $_SESSION['userId'] ?? 0;
if ($userId == 0) {
    header("Location: comp3340/public_html/index.php");
    exit();
}

?>

<main>
    <section class="card" style="margin: 0 auto; position: relative">
        <h2>Add Discussion</h2>
        <form method="post" action="../private/add_discussion_api.php">
            <div>
                <label for="title">Title</label>
                <input name="title" id="title" minlength="4" maxlength="200"></input>
            </div>
            <div>
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="16"></textarea>
            </div>

            <button type="submit" style="max-width: 500px; margin: 0 auto;">Add Discussion</button>
        </form>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>