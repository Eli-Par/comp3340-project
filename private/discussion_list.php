<?php
require_once 'discussion_card.php';

function createDiscussionList($discussionResult)
{
    if (!$discussionResult || $discussionResult->num_rows === 0) {
        echo "<p>No discussions available.</p>";
        return;
    }

    echo '<section class="discussion-list">';

    // Create a list of discussions by iterating over the entries
    while ($row = $discussionResult->fetch_assoc()) {

        $discussionId = $row['discussionId'];
        $title = $row['title'];
        $content = $row['content'];
        $username = $row['username'];
        $authorId = $row['authorId'];

        $hearts = $row['heartCount'];
        $isHearted = $row['heartedByUser'];

        echo createDiscussionCard($discussionId, $title, $content, $authorId, $username, $hearts, $isHearted);
    }

    echo '</section>';
}
?>