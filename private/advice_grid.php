<?php
require_once 'advice_card.php';

function createAdviceGrid($adviceResult)
{
    if (!$adviceResult || $adviceResult->num_rows === 0) {
        echo "<p>No advice available.</p>";
        return;
    }

    echo '<section class="advice-grid">';

    // Create a grid of advice by iterating over the entries
    while ($row = $adviceResult->fetch_assoc()) {
        
        $adviceId = $row['adviceId'];
        $title = $row['title'];
        $content = $row['content'];
        $username = $row['username'];
        
        $likes = $row['likeCount'];
        $dislikes = $row['dislikeCount'];
        
        $isLiked = $row['likedByUser'];
        $isDisliked = $row['dislikedByUser'];
        
        echo createAdviceCard($adviceId, $title, $content, $username, $likes, $dislikes, $isLiked, $isDisliked);
    }

    echo '</section>';
}
?>