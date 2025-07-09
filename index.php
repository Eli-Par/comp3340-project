<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include 'private/partial/head.php'; ?>
</head>

<?php include 'private/partial/header.php'; ?>

<main>
    <section class="card" style="max-width: 600px; margin: 0 auto;">
        <h2>Form Test</h2>
        <form>
            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Jane Doe" />
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="jane@example.com" />
            </div>

            <div>
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="">Select role</option>
                    <option value="developer">Developer</option>
                    <option value="designer">Designer</option>
                    <option value="manager">Manager</option>
                </select>
            </div>

            <div>
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Your message here..."></textarea>
            </div>

            <div>
                <input type="checkbox" id="checkbox" name="checkbox" />
                <label for="checkbox">Email</label>
            </div>

            <button type="reset" class="danger">Clear</button>
            <button type="submit">Submit</button>
        </form>
    </section>
</main>

<?php include 'private/partial/footer.php'; ?>