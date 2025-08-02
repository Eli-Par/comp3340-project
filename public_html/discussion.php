<?php
$pageDescription = "Read a user posted travel discussion on Travel Tipia. A travel tip site and discussion board for all your travel needs";
$pageKeywords = "user post, user generated content, travel discussion, discussion board, travel tips, travel, explore, adventure, community, tip, hub";

session_start();

require_once '../private/discussion_card.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$discussionId = $_GET['discussionId'] ?? 0;

//Get the discussion details
$query = "SELECT 
    a.discussionId, 
    a.title, 
    a.content, 
    a.authorId,
    users.username,
    a.dateCreated, 
    dateModified,

    -- Heart count queries
    (SELECT COUNT(*) FROM discussion_interactions a1 WHERE a1.discussionId = a.discussionId) AS heartCount,
    
    -- user hearted discussion?
    EXISTS (
        SELECT 1 FROM discussion_interactions a4
        WHERE a4.discussionId = a.discussionId AND a4.userId = ?
    ) AS heartedByUser
FROM discussion a JOIN users ON a.authorId = users.userId WHERE a.discussionId=?";

$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("ii", $userId, $discussionId);
$preparedStatement->execute();

$result = $preparedStatement->get_result();

//Get comments for discussion
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
    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="interactions.css" />
    <link rel="stylesheet" href="discussion.css" />

    <script src="discussion_interactions.js"></script>
    <script src="edit_discussion.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <!-- discussion section with title and content -->
    <section class="card" style="max-width: 90vw; margin: 0 auto;">
        <h2><?php echo htmlentities($title) ?></h2>
        <?php 
            //Format dates
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

    <!-- comment section card -->
    <section class="card comment-section-card">
        <h2>Comments</h2>
        <!-- Show comments or text explaining no comments yet -->
        <div id="comment-section">
            <?php if ($commentResult->num_rows == 0) { ?> <p id="no-comment-text" style="text-align: center;">No comments have been posted yet!</p> <?php } ?>
            <?php
            //Iterate over comments and put into cards
            while ($row = $commentResult->fetch_assoc()) {
                echo '
                <p class="card comment">
                    <b>' . htmlentities($row['username']) . ':</b> ' . nl2br(htmlentities($row['content'])) . '
                </p>
                ';
            }
            ?>
        </div>
        <!-- if user logged out show login prompt, otherwise show post a comment -->
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

        //When comment form submitted, send comment
        commentForm?.addEventListener('submit', (e) => {
            e.preventDefault();

            const content = document.getElementById('content').value;

            //Send comment content and discussion id to add comment api
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
                    //get comment section
                    const commentSection = document.getElementById('comment-section');

                    const noCommentText = document.getElementById('no-comment-text');
                    if(noCommentText && noCommentText.parentNode) {
                        noCommentText.parentNode.removeChild(noCommentText);
                    }
                    
                    //Create new comment card
                    const newComment = document.createElement('p');
                    newComment.classList.add('card');
                    newComment.classList.add('comment');
                    newComment.classList.add('new-comment');

                    //Insert comment contents
                    const userSection = document.createElement('b');
                    userSection.textContent = <?php echo json_encode($name); ?> + ': ';

                    const textSection = document.createTextNode(content);

                    newComment.appendChild(userSection);
                    newComment.appendChild(textSection);

                    //Add comment to list
                    commentSection.appendChild(newComment);

                    //Reset comment form
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

        //Update text area char count label
        const setCount = () => {
            const text = commentTextarea?.value ?? '';

            const length = text.length;

            const commentMaxLabel = document.getElementById('comment-max');

            if(commentMaxLabel) commentMaxLabel.textContent = `(${length} / 1000 characters)`;
        };

        //Update comment max char count label at start and when edited
        setCount();
        commentTextarea?.addEventListener('input', () => {
            setCount();
        });
    });
</script>

<?php include '../private/partial/footer.php'; ?>