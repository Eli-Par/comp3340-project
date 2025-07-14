<?php
session_start();

require_once '../private/discussion_card.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$discussionId = $_GET['discussionId'] ?? 0;

//Get the discussion details
$query = "SELECT 
    discussionId, 
    title, 
    content, 
    username,
    dateCreated, 
    dateModified,

    -- Heart count queries
    (SELECT COUNT(*) FROM discussion_interactions a1 WHERE a1.discussionId = a.discussionId) AS heartCount,
    
    -- Booleans for current user
    EXISTS (
        SELECT 1 FROM discussion_interactions a4
        WHERE a4.discussionId = a.discussionId AND a4.userId = ?
    ) AS heartedByUser
FROM discussion a JOIN users ON a.authorId = users.userId WHERE a.discussionId=?";

$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("ii", $userId, $discussionId);
$preparedStatement->execute();

$result = $preparedStatement->get_result();

$queryComments = "SELECT 
        username, 
        content, 
        dateCreated

    FROM discussion_comments a JOIN users ON a.authorId = users.userId WHERE a.discussionId=? ORDER BY commentId ASC";

$prepStmt = $conn->prepare($queryComments);
$prepStmt->bind_param("i", $discussionId);
$prepStmt->execute();

$commentResult = $prepStmt->get_result();

//If the discussion exists get all its properties, otherwise set defaults
if ($result->num_rows > 0) {
    $discussion = $result->fetch_assoc();

    $title = $discussion["title"] ?? "";
    $content = $discussion["content"] ?? "";
    $username = $discussion["username"] ?? "";

    $hearts = $discussion["heartCount"];
    $isHearted = $discussion["heartedByUser"];
} else {
    $title = "Discussion not found";
    $content = "";
    $username = "Unknown";

    $hearts = 0;
    $isHearted = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="interactions.css" />
    <link rel="stylesheet" href="discussion.css" />

    <script src="discussion_interactions.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="card" style="max-width: 90vw; margin: 0 auto;">
        <h2><?php echo htmlentities($title) ?></h2>
        <?php 
            $dateCreated = date("F j, Y", strtotime($discussion["dateCreated"]));
            $dateModified = date("F j, Y", strtotime($discussion["dateModified"]));

            // Show date modified if different
            $showModified = ($discussion["dateCreated"] != $discussion["dateModified"]);
        ?>
        <div style="display: flex; justify-content: center; align-items: center; gap: 10px; font-size: 1.1em;">
            <span>By <strong><?php echo htmlentities($username); ?></strong></span>
            <span>Created: <?php echo $dateCreated; ?></span>
            <?php if ($showModified) { ?>
                <span>Modified: <?php echo $dateModified; ?></span>
            <?php } ?>
        </div>

        <div style="display: flex; justify-content: center;">
            <?php echo createDiscussionInteractionButtons($discussionId, $hearts, $isHearted, true) ?>
        </div>

        <p style="text-align: center; margin-top: 10px;">
            <?php echo nl2br(htmlspecialchars($content)) ?>
        </p>
    </section>

    <section class="card comment-section-card">
        <h2>Comments</h2>
        <?php if ($commentResult->num_rows == 0) { ?>
            <p style="text-align: center;">No comments have been posted yet!</p>
        <?php } else { ?>
            <div id="comment-section">
                <?php
                while ($row = $commentResult->fetch_assoc()) {
                    echo '
                    <p class="card comment">
                        <b>' . htmlentities($row['username']) . ':</b> ' . nl2br(htmlentities($row['content'])) . '
                    </p>
                    ';
                }
                ?>
            </div>
        <?php } ?>
        <?php if($userId == 0) { ?>
            <a href="login.php" style="color: black; text-align: center;"><h3 style="margin-top: 16px;">Login to Post a Comment</h3></a>
        <?php } else { ?>
            <form class="comment-form" id="comment-form">
            <div>
                <h3>Post a Comment</h3>
                <label for="content"><span class="label-aside" id="comment-max">(0 / 1000 characters)</span></label>
                <textarea required name="content" id="content" maxlength="1000"></textarea>
            </div>
            <button type="submit">Post Comment</button>
        </form>
        <?php } ?>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const commentForm = document.getElementById('comment-form');

        commentForm?.addEventListener('submit', (e) => {
            e.preventDefault();

            const content = document.getElementById('content').value;

            const formData = new URLSearchParams();
            formData.append('content', content);
            formData.append('discussionId', <?php echo json_encode((int) $discussionId); ?>)
            fetch('../private/add_comment_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const commentSection = document.getElementById('comment-section');
                    
                    const newComment = document.createElement('p');
                    newComment.classList.add('card');
                    newComment.classList.add('comment');
                    newComment.classList.add('new-comment');

                    const userSection = document.createElement('b');
                    userSection.textContent = <?php echo json_encode($username); ?> + ': ';

                    const textSection = document.createTextNode(content);

                    newComment.appendChild(userSection);
                    newComment.appendChild(textSection);

                    commentSection.appendChild(newComment);

                    commentForm.reset();
                }
                else {
                    console.error(data);
                }
            })
            .catch(error => console.error(error));

        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const commentTextarea = document.getElementById('content');

        const setCount = () => {
            const text = commentTextarea?.value ?? '';

            const length = text.length;

            const commentMaxLabel = document.getElementById('comment-max');

            if(commentMaxLabel) commentMaxLabel.textContent = `(${length} / 1000 characters)`;
        };

        setCount();
        commentTextarea?.addEventListener('input', () => {
            setCount();
        });
    });
</script>

<?php include '../private/partial/footer.php'; ?>