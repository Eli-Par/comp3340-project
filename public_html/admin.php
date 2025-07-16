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

    <link rel="stylesheet" href="my_profile.css" />

    <script src="https://cdn.plot.ly/plotly-2.30.0.min.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="card">
        <h2>Admin Pages</h2>
    </section>

    <section class="card" style="margin-top: 16px;">
        <h2>Discussion Posts by Day Created</h2>
        <div id="chart"></div>
    </section>
</main>

<script>
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

<?php include '../private/partial/footer.php'; ?>