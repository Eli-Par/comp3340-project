<?php
session_start();

//If not logged in redirect to home page
$userId = $_SESSION['userId'] ?? 0;
if ($userId == 0) {
    header("Location: /comp3340/public_html/index.php");
    exit();
}

require '../private/dbConnection.php';

//Get current user profile information
$statement = $conn->prepare("SELECT username, joinDate, bio FROM users WHERE userId = ?");
$statement->bind_param("i", $userId);
$statement->execute();
$result = $statement->get_result();
$user = $result->fetch_assoc();

//Setup user details including join date
$username = $user["username"];
$joinDate = new DateTime($user['joinDate']);
$bio = $user['bio'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="my_profile.css" />
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <!-- user profile card with settings icon to edit account -->
    <section class="card" style="max-width: 600px; margin: 0 auto; position: relative">
        <h2>My Profile</h2>
        <a href="account.php" class="settings">
            <span class="material-symbols-outlined">settings</span>
        </a>
        <!-- display user details including formatted join date -->
        <p style="text-align: center;">Joined on <?php echo htmlentities($joinDate->format('F j, Y')) ?></p>
        <p style="margin-top: 10px;"><b>Username:</b> <?php echo htmlentities($username) ?></p>
        <p style="margin-top: 10px;"><b>Bio:</b> <?php echo htmlentities($bio) ?></p>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>