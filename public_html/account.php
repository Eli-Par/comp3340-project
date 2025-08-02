<?php
$pageDescription = "A travel tip site and discussion board for all your travel needs";
$pageKeywords = "edit account, edit profile, travel, explore, adventure, community, tip, hub";

session_start();

//Check user logged in
$userId = $_SESSION['userId'] ?? 0;
if ($userId == 0) {
    header("Location: /comp3340/public_html/index.php");
    exit();
}

require '../private/dbConnection.php';

//Get logged in user details
$statement = $conn->prepare("SELECT username, joinDate, bio FROM users WHERE userId = ?");
$statement->bind_param("i", $userId);
$statement->execute();
$result = $statement->get_result();
$user = $result->fetch_assoc();

$username = $user["username"];
$joinDate = new DateTime($user['joinDate']);
$bio = $user['bio'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="my_profile.css" />

    <script src="bio_char_count.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <!-- profile edit form card -->
    <section class="card" style="max-width: 500px; margin: 0 auto; position: relative">
        <h2>Edit Profile</h2>
        <a href="my_profile.php" class="settings">
            <span class="material-symbols-outlined">visibility</span>
        </a>
        <p style="text-align: center;">Joined on <?php echo htmlentities($joinDate->format('F j, Y')) ?></p>
        <form method="post" action="../private/update_user_api.php">
            <div>
                <label for="username">Username</label>
                <input name="username" id="username" value="<?php echo htmlspecialchars($username) ?>" minlength="4" maxlength="50"></input>
            </div>
            <div>
                <label for="bio">Bio <span class="label-aside" id="bio-max">(0 / 500 characters)</span></label>
                <textarea name="bio" id="bio" rows="8" maxlength="500"><?php echo htmlspecialchars($bio) ?></textarea>
            </div>

            <button type="submit">Save Profile</button>
        </form>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>