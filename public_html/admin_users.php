<?php
session_start();

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;
if ($userId == 0 || !$isAdmin) {
    header("Location: /comp3340-project/public_html/index.php");
    exit();
}

require_once '../private/dbConnection.php';

$stmt = $conn->prepare('SELECT * FROM users');
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="admin_users.css" />

    <script src="https://cdn.plot.ly/plotly-2.30.0.min.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="card">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <th>User</th>
                <th>Status</th>
            </thead>
            <?php
            while( $row = $result->fetch_assoc() ) { ?>
                <tr>
                    <td><?php echo htmlentities($row['username']) ?></td>
                    <td><input id="user-input-<?php echo htmlentities($row['userId']) ?>" type="checkbox" <?php echo $row['isActive'] == 1 ? 'checked' : '' ?> data-user-id="<?php echo htmlentities($row['userId']) ?>" /><label for="user-input-<?php echo htmlentities($row['userId']) ?>">Is Active</label></td>
                </tr>
            <?php } ?>
        </table>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[type="checkbox"]').forEach(input => {
            input.addEventListener('input', () => {
                const formData = new URLSearchParams();
                formData.append('userId', input.getAttribute('data-user-id'));
                formData.append('isActive', input.checked ? 1 : 0);
                fetch('../private/user_status_api.php', {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: formData
                }).then(res => res.json()).then((data) => {
                    console.log(data);
                });
            });
        });
    });
</script>

<?php include '../private/partial/footer.php'; ?>