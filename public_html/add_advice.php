<?php
session_start();

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;
if ($userId == 0 || !$isAdmin) {
    header("Location: /comp3340-project/public_html/index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="add_advice.css" />
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="card" style="margin: 0 auto; position: relative">
        <h2>Add Advice</h2>
        <form method="post" action="../private/add_advice_api.php">
            <div>
                <label for="title">Title</label>
                <input name="title" id="title" minlength="4" maxlength="200"></input>
            </div>
            <div>
                <label for="imageLink">Image Link</label>
                <input name="imageLink" id="imageLink" maxlength="256"></input>
            </div>
            <div>
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="16"></textarea>
            </div>
            <div>
                <label for="summary">Summary</label>
                <textarea name="summary" id="summary" rows="3"></textarea>
            </div>

            <button type="submit" style="max-width: 500px; margin: 0 auto;">Add Advice</button>
        </form>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>