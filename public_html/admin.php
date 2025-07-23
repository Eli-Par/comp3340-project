<?php
session_start();

//Check if is admin
$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;
if ($userId == 0 || !$isAdmin) {
    header("Location: /comp3340-project/public_html/index.php");
    exit();
}

require_once '../private/dbConnection.php';

// Get the theme currently selected
$currentTheme = 'regular';
$result = $conn->query("SELECT theme FROM themes LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    $currentTheme = $row['theme'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="my_profile.css" />

    <script src="https://cdn.plot.ly/plotly-2.30.0.min.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <!-- Show different theme options and check them if selected -->
    <section class="card" style="max-width: 600px; margin: 20px auto; padding: 16px;">
        <h2>Select Theme</h2>
        <form id="themeForm">
            <div style="display: flex; justify-content: space-around;">
                <div>
                    <label style="display: inline-block;" for="regular-radio">Regular</label>
                    <input style="width: fit-content" type="radio" name="theme" id="regular-radio" value="regular" <?php if ($currentTheme === 'regular')
                            echo 'checked'; ?>>
                </div>
                
                <div>
                    <label style="display: inline-block;" for="sharp-radio">Sharp</label>
                    <input style="width: fit-content" type="radio" name="theme" id="sharp-radio" value="sharp" <?php if ($currentTheme === 'sharp')
                            echo 'checked'; ?>>
                </div>
                
                <div>
                    <label style="display: inline-block;" for="halloween-radio">Halloween</label>
                    <input style="width: fit-content" type="radio" name="theme" id="halloween-radio" value="halloween" <?php if ($currentTheme === 'halloween')
                            echo 'checked'; ?>>
                </div>
            </div>
        </form>
    </section>

    <!-- Show other admin pages for nav -->
    <section class="card" style="width: fit-content; margin: 20px auto;">
        <h2>Admin Pages</h2>
        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 10px;">
            <button onclick="window.location.href='admin_users.php'" style="width: fit-content; display: inline-block;">Manage Users</button>
            <button onclick="window.location.href='add_advice.php'" style="width: fit-content; display: inline-block;">Add Advice</button>
            <button onclick="window.location.href='admin_contact.php'" style="width: fit-content; display: inline-block;">Contact Messages</button>
        </div>
    </section>

    <!-- Card for discussion posts over time graph -->
    <section class="card" style="margin-top: 16px;">
        <h2>Discussion Posts by Day Created</h2>
        <div id="chart"></div>
    </section>
</main>

<script>
    //Call api to get discussion post stats and plot line graph
    document.addEventListener('DOMContentLoaded', () => {
        fetch('../private/stat_api.php')
        .then(res => res.json())
        .then(json => {
            if (!json.success) return;

            const trace = {
                x: json.data.x,
                y: json.data.y,
                type: 'scatter',
                mode: 'lines+markers',
                name: 'Discussion Posts'
            };

            Plotly.newPlot('chart', [trace]);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        //When a theme control changes value send api request to update theme and reload page.
        document.getElementById('themeForm').addEventListener('change', function(e) {
            if (e.target.name === 'theme') {
                const theme = e.target.value;

                const formData = new FormData();
                formData.append('theme', theme);

                fetch('../private/theme_api.php', {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); //Reload page on success
                    } else {
                        alert('Failed to update theme: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(() => alert('Network or server error'));
            }
        });
    });
</script>

<?php include '../private/partial/footer.php'; ?>