<?php
    $pageDescription = "Login to Travel Tipia to gain access to all features!. Travel Tipia is a travel tip site and discussion board for all your travel needs";
    $pageKeywords = "signin, travel tips, travel, explore, adventure, community, tip, hub";
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="login.css" />
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <!-- login form card -->
    <section class="card" style="max-width: 600px; margin: 0 auto;">
        <h2>Login</h2>
        <form action="../private/login_api.php" method="post">
            <?php //If invalid error display incorrect username or password message
            if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
                ?>
            <div class="error">
                Username or password is incorrect
            </div>
            <?php
            }
            ?>

            <?php //if disabled error display that users account is no longer active
            if (isset($_GET['error']) && $_GET['error'] == 'disabled') {
                ?>
                <div class="error">
                    Your account has been disabled
                </div>
                <?php
            }
            ?>

            <!-- login form fields -->
            <div>
                <label for="name">Usename</label>
                <input required type="text" id="username" name="username" />
            </div>

            <div>
                <label for="password">Password</label>
                <input required type="password" id="password" name="password" />
            </div>

            <button type="submit">Login</button>
            <a style="margin: 0 auto;" href="signup.php">Don't have an account? Create one!</a>
        </form>

    </section>
</main>

<?php include '../private/partial/footer.php'; ?>