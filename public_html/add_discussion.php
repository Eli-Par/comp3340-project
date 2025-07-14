<?php
session_start();

$userId = $_SESSION['userId'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="add_discussion.css" />
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="card" style="margin: 0 auto; position: relative">
        <h2>Add Discussion</h2>
        <?php
        if ($userId != 0) {
            ?>
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
        <?php } else { ?>
            <p style='text-align: center;'>You need to be logged in to post a discussion.</p>
            <button style="margin-top: 16px;" onclick="window.location.href='login.php'">Login Here</button>
        <?php } ?>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>