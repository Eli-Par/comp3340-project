<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="login.css" />
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="card" style="max-width: 600px; margin: 0 auto;">
        <h2>Login</h2>
        <form action="../private/login_api.php" method="post">
            <?php
            if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
                ?>
            <div class="error">
                Username or password is incorrect
            </div>
            <?php
            }
            ?>

            <?php
            if (isset($_GET['error']) && $_GET['error'] == 'disabled') {
                ?>
                <div class="error">
                    Your account has been disabled
                </div>
                <?php
            }
            ?>

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