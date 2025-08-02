<?php
$pageDescription = "A travel tip site and discussion board for all your travel needs";
$pageKeywords = "view contact messages, travel, explore, adventure, community, tip, hub";

session_start();

//Check if admin
$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;
if ($userId == 0 || !$isAdmin) {
    header("Location: /comp3340-project/public_html/index.php");
    exit();
}

require_once '../private/dbConnection.php';

//Get all contact messages
$stmt = $conn->prepare('SELECT * FROM contact_messages');
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="admin_contact.css" />

    <script src="https://cdn.plot.ly/plotly-2.30.0.min.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <h1 style="margin-bottom: 10px;">Contact Messages</h1>
    <div class="messages-container">
        <?php
        //Iterate over contact messages and display them in cards
        while ($row = $result->fetch_assoc()) {
            ?>
            <section class="card">
                <h2><?php echo htmlentities($row['subject']) ?></h2>
                <p style="text-align: center; margin-bottom: 10px;">
                    <b>
                        From <?php echo htmlentities($row['name']) ?>
                        <?php 
                        if ($row['email']) {
                            echo ' | ' . htmlentities($row['email']);
                        }
                        ?>
                    </b>
                </p>
                <p style="text-align: center;">
                    <?php echo nl2br(htmlentities($row['message'])) ?>
                </p>
            </section>
            <?php
        }
        ?>
    </div>
</main>

<?php include '../private/partial/footer.php'; ?>