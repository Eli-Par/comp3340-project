<?php
session_start();

require_once '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;
$discussionId = $_GET['discussionId'] ?? 0;

//Check that user logged in and discussion id provided
if ($userId == 0 || $discussionId == 0) {
    header("Location: index.php");
    exit();
}

// get discussion to edit
$query = "SELECT discussionId, title, content, authorId FROM discussion WHERE discussionId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $discussionId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Discussion not found.");
}

$discussion = $result->fetch_assoc();

// Check if user is author or admin
if (!$isAdmin && $discussion['authorId'] != $userId) {
    die("You do not have permission to edit this discussion.");
}

$title = $discussion['title'];
$content = $discussion['content'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    
    <?php include '../private/partial/head.php'; ?>
    <link rel="stylesheet" href="add_advice.css" />
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <!-- edit discusson form card, loading discussions original details into form as initial values -->
    <section class="card" style="margin: 0 auto; position: relative; max-width: 600px;">
        <h2>Edit Discussion</h2>
        <form method="post" action="../private/edit_discussion_api.php">
            <!-- hidden field to send discussonId -->
            <input type="hidden" name="discussionId" value="<?php echo (int) $discussionId; ?>" />

            <div>
                <label for="title">Title</label>
                <input name="title" id="title" minlength="4" maxlength="200" required
                    value="<?php echo htmlentities($title); ?>" />
            </div>
            <div>
                <label for="content">Content</label>
                <textarea name="content" id="content" rows="16"
                    required><?php echo htmlentities($content); ?></textarea>
            </div>

            <button type="submit" style="max-width: 500px; margin: 0 auto;">Save Changes</button>
        </form>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>