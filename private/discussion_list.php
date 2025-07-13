<?php
require_once 'discussion_card.php';

function createDiscussionList($discussionResult)
{
    if (!$discussionResult || $discussionResult->num_rows === 0) {
        echo "<p>No discussions available.</p>";
        return;
    }

    echo '<section class="discussion-list">';

    // Create a grid of advice by iterating over the entries
    while ($row = $discussionResult->fetch_assoc()) {

        $discussionId = $row['discussionId'];
        $title = $row['title'];
        $content = $row['content'];
        $username = $row['username'];

        $hearts = $row['heartCount'];
        $isHearted = $row['heartedByUser'];

        echo createDiscussionCard($discussionId, $title, $content, $username, $hearts, $isHearted);
    }

    echo '</section>';
}
?>