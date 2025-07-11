<?php

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
        <h2>Sign Up</h2>
        <form action="../private/signup_api.php" method="post">
            <?php if (isset($_GET['error']) && $_GET['error'] == 'exists') {?>
                <div class="error">Username already exists</div>
            <?php }?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'passwordMatch') {?>
                <div class="error">Passwords entered do not match</div>
            <?php }?>

            <?php if (isset($_GET['error']) && ($_GET['error'] == 'username' || $_GET['error'] == 'password')) {?>
                <div class="error">Username and password are required</div>
            <?php }?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'usernameLength') { ?>
                <div class="error">Username must be at least 4 characters long and no longer than 50 characters</div>
            <?php } ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'passwordLength') { ?>
                    <div class="error">Password must be at least 8 characters long and no longer than 100 characters</div>
            <?php } ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'bio') { ?>
                <div class="error">Bio cannot be more than 500 characters long</div>
            <?php } ?>

            <div>
                <label for="name">Usename</label>
                <input required type="text" id="username" name="username" minlength="4" maxlength="50" />
            </div>

            <div>
                <label for="password">Password</label>
                <input required type="password" id="password" name="password" minlength="8" maxlength="100" />
            </div>

            <div>
                <label for="confirmPassword">Confirm Password</label>
                <input required type="password" id="confirmPassword" name="confirmPassword" minlength="8" maxlength="100" />
            </div>

            <div>
                <label for="bio">Bio <span class="label-aside" id="bio-max">(0 / 500 characters)</span></label>
                <textarea id="bio" name="bio" maxlength="500"></textarea>
            </div>

            <button type="submit">Sign Up</button>
            <a style="margin: 0 auto;" href="login.php">Already have an account? Login here!</a>
        </form>

    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bio = document.getElementById('bio');
        bio.addEventListener('input', () => {
            const text = bio.value;

            const length = text.length;

            const bioMaxLabel = document.getElementById('bio-max');

            bioMaxLabel.textContent = `(${length} / 500 characters)`;
        })
    });
</script>

<?php include '../private/partial/footer.php'; ?>