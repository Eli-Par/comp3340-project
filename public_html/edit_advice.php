<?php
session_start();

require_once '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;
$adviceId = $_GET['adviceId'] ?? 0;

//check that user logged in and is admin
if ($userId == 0 || !$isAdmin || $adviceId == 0) {
    header("Location: index.php");
    exit();
}

//get advice
$query = "SELECT * FROM advice WHERE adviceId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $adviceId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Advice not found.");
}

$advice = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../private/partial/head.php'; ?>
    <link rel="stylesheet" href="add_advice.css" /> <!-- reuse add_advice.css since formatting is the same -->
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="card" style="margin: 0 auto; position: relative;">
        <h2>Edit Advice</h2>
        <!-- edit form -->
        <form method="post" action="../private/edit_advice_api.php">
            <!-- hidden field to store and send adviceId -->
            <input type="hidden" name="adviceId" value="<?php echo (int) $adviceId; ?>" />

            <div>
                <label for="title">Title</label>
                <input name="title" id="title" minlength="4" maxlength="200" required
                    value="<?php echo htmlentities($advice['title']); ?>" />
            </div>
            <div>
                <label for="imageLink">Image Link</label>
                <input name="imageLink" id="imageLink" maxlength="256"
                    value="<?php echo htmlentities($advice['imageLink']); ?>" />
            </div>
            <div>
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="16"
                    required><?php echo htmlentities($advice['content']); ?></textarea>
            </div>
            <div>
                <label for="summary">Summary</label>
                <textarea name="summary" id="summary"
                    rows="3"><?php echo htmlentities($advice['summary']); ?></textarea>
            </div>

            <button type="submit" style="max-width: 500px; margin: 0 auto;">Save Changes</button>
        </form>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>